"use strict";
/* global PostCreate, FileUpload, trans, getWebsiteFormattedAmount, YouTubeEmbed */

// Presentation only: pricing, polls and scheduling remain owned by PostCreate.
var PostComposer = {
    root: null,
    startedAt: 0,
    youtubePreview: null,
    youtubeAllowedHosts: null,

    init: function () {
        PostComposer.root = document.querySelector('.post-composer');
        if(!PostComposer.root) return;
        PostComposer.startedAt = Date.now();
        PostCreate.postReleaseDate = $('#post_release_date').val() || null;
        PostCreate.postExpireDate = $('#post_expire_date').val() || null;
        PostComposer.initYouTubePreview();
        PostComposer.refresh();
    },

    initYouTubePreview: function () {
        PostComposer.youtubePreview = null;
        PostComposer.youtubeAllowedHosts = null;
        if(PostComposer.root.dataset.enableYoutubeEmbeds !== 'true' || typeof YouTubeEmbed === 'undefined') return;
        const preview = PostComposer.root.querySelector('.post-composer-youtube-preview');
        if(!preview) return;
        try {
            PostComposer.youtubeAllowedHosts = JSON.parse(PostComposer.root.dataset.youtubeAllowedHosts);
            if(!Array.isArray(PostComposer.youtubeAllowedHosts)) return;
        } catch (error) {
            // Saving still validates links on the server; hide a preview with an invalid policy.
            return;
        }
        PostComposer.youtubePreview = preview;
        $('#dropzone-uploader').off('.postComposerYouTube')
            .on('input.postComposerYouTube change.postComposerYouTube', PostComposer.refreshYouTubePreview)
            .on('paste.postComposerYouTube', function () {
                // The paste event precedes the textarea's new value.
                window.requestAnimationFrame(PostComposer.refreshYouTubePreview);
            });
    },

    refreshYouTubePreview: function () {
        const preview = PostComposer.youtubePreview;
        if(!preview) return;
        const videoIds = YouTubeEmbed.videoIds($('#dropzone-uploader').val(), PostComposer.youtubeAllowedHosts);
        preview.hidden = videoIds.length === 0;
        preview.querySelector('.post-composer-youtube-title').textContent = videoIds.length === 1
            ? trans('YouTube video')
            : trans(':count YouTube videos').replace(':count', videoIds.length);
    },

    // Keep the shared uploader's previews; only track the post's count and order.
    bindUploads: function (dropzone) {
        ['addedfile', 'removedfile'].forEach(function (event) {
            dropzone.on(event, function () {
                PostComposer.updateMediaCount(dropzone);
                PostCreate.scheduleDraftSave();
            });
        });
        dropzone.on('success', function (file, response) {
            if(!response.success || dropzone.files.indexOf(file) < 0) return;
            // A faster upload must not jump ahead of an earlier selection.
            const ids = dropzone.files.map(function (item) { return String(item.upload && item.upload.attachmentID); });
            FileUpload.attachaments.sort(function (a, b) { return ids.indexOf(String(a.attachmentID)) - ids.indexOf(String(b.attachmentID)); });
            PostCreate.scheduleDraftSave();
        });
        PostComposer.updateMediaCount(dropzone);
    },

    updateMediaCount: function (dropzone) {
        const count = dropzone.files.length;
        $(PostComposer.root).find('.post-composer-media-count').prop('hidden', count === 0)
            .text(count === 1 ? trans('1 attachment') : trans(':count attachments').replace(':count', count));
    },

    submitLabel: function () {
        const root = PostComposer.root;
        if(!root) return trans('Save');
        if(root.dataset.mode === 'edit') return trans('Save changes');
        if(root.dataset.needsReview === 'true') return trans('Submit for review');
        // datetime-local fields use server wall time, not the viewer's timezone.
        const serverNow = Date.parse(root.dataset.serverTime + 'Z') + Date.now() - PostComposer.startedAt;
        const release = PostCreate.postReleaseDate && Date.parse(PostCreate.postReleaseDate.replace(' ', 'T') + 'Z');
        return release > serverNow ? trans('Schedule post') : trans('Post');
    },

    refresh: function () {
        if(!PostComposer.root) return;
        PostComposer.refreshYouTubePreview();
        const root = $(PostComposer.root);
        const paid = Number(PostCreate.postPrice) > 0;
        const price = paid ? getWebsiteFormattedAmount(PostCreate.postPrice) : trans('Not set');
        root.find('.post-price-label').text(paid ? price : '');
        root.find('.post-price-summary').prop('hidden', !paid);
        root.find('.post-price-button').attr('aria-label', trans('Set post price') + ': ' + price);
        const dates = [];
        if(PostCreate.postReleaseDate) dates.push(trans('Release') + ': ' + PostCreate.postReleaseDate.replace('T', ' '));
        if(PostCreate.postExpireDate) dates.push(trans('Expires') + ': ' + PostCreate.postExpireDate.replace('T', ' '));
        root.find('.post-schedule-summary').prop('hidden', !dates.length).find('span').last()
            .text(dates.join(' · ') + (dates.length ? ' (' + PostComposer.root.dataset.timezone + ')' : ''));
        root.find('.post-poll-summary').prop('hidden', !PostCreate.pollAnswers.length);
        root.find('.post-poll-check').prop('hidden', !PostCreate.pollAnswers.length);
        root.find('#post-poll-state').text(trans(PostCreate.pollAnswers.length ? 'Configured' : 'Not set'));
        root.find('.post-schedule-check').prop('hidden', !dates.length);
        root.find('#post-schedule-state').text(trans(dates.length ? 'Configured' : 'Not set'));
        root.find('.post-notification-summary, .post-notification-check').prop('hidden', !PostCreate.postNotifications);
        root.find('.post-notification-button').attr('aria-pressed', String(PostCreate.postNotifications));
        const hasSettings = (paid && root.find('.post-price-summary').length)
            || (dates.length && root.find('.post-schedule-summary').length)
            || (PostCreate.pollAnswers.length && root.find('.post-poll-summary').length)
            || (PostCreate.postNotifications && root.find('.post-notification-summary').length);
        root.find('.post-composer-settings').prop('hidden', !hasSettings);
        if(!PostCreate.isSaving) root.find('.post-create-button').text(PostComposer.submitLabel());
    }
};
