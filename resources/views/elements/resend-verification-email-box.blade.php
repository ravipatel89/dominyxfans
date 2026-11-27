<div class="alert alert-primary alert-dismissible text-white unverified-email-box" role="alert">
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    <h5 class="alert-heading fw-bold">{{__('Warning')}}!</h5>
    <p class="m-0">{{__("You have not confirmed your email address yet")}}. {{__("Click on")}} <a class="fw-bold text-white resend-verification-btn" href="javascript:void(0)" onClick="sendEmailConfirmation()">{{__("this link")}}</a> {{__("to resend the confirmation email")}}.</p>
</div>
