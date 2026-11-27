<div class="alert alert-primary alert-dismissible text-white unverified-email-box mb-0" role="alert">
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    <p class="m-0">
        {{trans_choice("You only have :approved approved posts.", PostsHelper::getUserApprovedPostsCount(Auth::user()->id),['approved' => PostsHelper::getUserApprovedPostsCount(Auth::user()->id)])}}
        {{trans_choice("Your next :tbapproved posts will have to admin approved.", PostsHelper::getPostsCountLeftTillAutoApprove(Auth::user()->id),['tbapproved' => PostsHelper::getPostsCountLeftTillAutoApprove(Auth::user()->id)])}} </p>
</div>
