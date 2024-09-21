<!-- ------------------------------------------------- -->
<!-- Ticket Action -->
<!-- ------------------------------------------------- -->
<div class="card mt-3"> <!-- Main Card Start -->
    <div class="card-header">
        Ticket Action
    </div>
    <div class="card-body"><!-- Main Card BODY START -->

        <!-- Accordion Start -->
        <div class="accordion" id="ticketAction">

            <!-- Ticket Reply -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReply" aria-expanded="true" aria-controls="collapseReply">
                    Reply
                </button>
                </h2>
                <div id="collapseReply" class="accordion-collapse collapse show" data-bs-parent="#ticketAction">
                    <div class="accordion-body">
                        <!-- Ticket Reply Content START-->
                        @include('tdtickets::partials.ticketActionReply')
                        <!-- Ticket Reply Content END-->
                    </div>
                </div>
            </div>

            <!-- Ticket Time Track -->
            <!--
            <div class="accordion-item">
                <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTime" aria-expanded="false" aria-controls="collapseTwo">
                    Placeholder
                </button>
                </h2>
                <div id="collapseTime" class="accordion-collapse collapse" data-bs-parent="#ticketAction">
                    <div class="accordion-body">

                    </div>
                </div>
            </div>-->

        </div><!-- Accordion End -->
    </div><!-- Main Card BODY END -->
</div><!-- Main Card END -->