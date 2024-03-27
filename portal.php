<style>
	#chat_convo{
		max-height: 65vh;
	}
	#chat_convo .direct-chat-messages{
		min-height: 250px;
		height: inherit;
	}
	#chat_convo .card-body {
		overflow: auto;
	}
</style>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-8 <?php echo isMobileDevice() == false ?  "offset-2" : '' ?>">
			<div class="card direct-chat direct-chat-primary" id="chat_convo">
              <div class="card-header ui-sortable-handle" style="cursor: move;">
                <h3 class="card-title">Ask Me</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <!-- Conversations are loaded here -->
                <div class="direct-chat-messages">
                  <!-- Message. Default to the left -->
                  <div class="direct-chat-msg mr-4">
                    <img class="direct-chat-img border-1 border-primary" src="<?php echo validate_image($_settings->info('bot_avatar')) ?>" alt="message user image">
                    <!-- /.direct-chat-img -->
                    <div class="direct-chat-text">
                      <?php echo "Welcome to medical chatbot";//$_settings->info('intro') ?>
                    </div>
                    <!-- /.direct-chat-text -->
                  </div>
                  <!-- /.direct-chat-msg -->

                  <!-- /.contacts-list -->
                </div>
                <div class="end-convo"></div>
                <!-- /.direct-chat-pane -->
              </div>
              <!-- /.card-body -->

              <div class="card-footer">

									<div class="row" style="background: cyan">
											<div class="col-lg-6">
	                      <div class="icheck-primary d-inline">
	                        <input type="radio" id="radioPrimary1" name="r1" checked>
	                        <label for="radioPrimary1"> Summarize
	                        </label>
	                      </div>
											</div>
											<div class="col-lg-6">
	                      <div class="icheck-primary d-inline">
	                        <input type="radio" id="radioPrimary2" name="r1">
	                        <label for="radioPrimary2">Extract
	                        </label>
	                      </div>
											</div>
									</div>
									<br />
								 <form id="send_chat" method="post">
                  <div class="input-group">
                    <textarea type="text" name="message" placeholder="Enter text ..." class="form-control" required=""></textarea>
                    <span class="input-group-append">
                      <button type="submit" class="btn btn-primary">Send</button>
                    </span>
                  </div>
                </form>
              </div>
              <!-- /.card-footer-->
            </div>
		</div>
	</div>
</div>
<div class="d-none" id="user_chat">
	<div class="direct-chat-msg right  ml-4">
        <img class="direct-chat-img border-1 border-primary" src="<?php echo validate_image($_settings->info('user_avatar')) ?>" alt="message user image">
        <!-- /.direct-chat-img -->
        <div class="direct-chat-text"></div>
        <!-- /.direct-chat-text -->
    </div>
</div>
<div class="d-none" id="bot_chat">
	<div class="direct-chat-msg mr-4">
        <img class="direct-chat-img border-1 border-primary" src="<?php echo validate_image($_settings->info('bot_avatar')) ?>" alt="message user image">
        <!-- /.direct-chat-img -->
        <div class="direct-chat-text"></div>
        <!-- /.direct-chat-text -->
  </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('[name="message"]').keypress(function(e){
			console.log()
			if(e.which === 13 && e.originalEvent.shiftKey == false){
				$('#send_chat').submit()
				return false;
			}
		})

	})
</script>
<script type="importmap">
	{
		"imports": {
			"@google/generative-ai": "https://esm.run/@google/generative-ai"
		}
	}
</script>
<script type="module">
	import { GoogleGenerativeAI } from "@google/generative-ai";

	// Fetch your API_KEY
	const API_KEY = "AIzaSyA6NTyyV8NA-Rr6ZEq-iBMo_O4okHCVJmM";

	// Access your API key (see "Set up your API key" above)
	const genAI = new GoogleGenerativeAI(API_KEY);

	// ...

	const model = genAI.getGenerativeModel({ model: "gemini-pro"});
	// console.logmodelhhh', model);

	// Control user input here

	 $('#send_chat').submit(async function(e){
		e.preventDefault();
		var promptMessage;
		var message = $('[name="message"]').val();
		var checkboxValue = $("#radioPrimary1").prop("checked");
		if (checkboxValue===true) {
			promptMessage = 'Sumarize medical terms '+message;
		}else {
			promptMessage = 'Extract medical terms '+message;
		}
		// console.log(promptMessage);
		start_loader();
		// if(message == '' || message == null) alert_toast("Pls Enter a long text.",'error'); return false;
		var uchat = $('#user_chat').clone();
		uchat.find('.direct-chat-text').html(message);
		$('#chat_convo .direct-chat-messages').append(uchat.html());
		$('[name="message"]').val('')
		$("#chat_convo .card-body").animate({ scrollTop: $("#chat_convo .card-body").prop('scrollHeight') }, "fast");

			const prompt = promptMessage;
			const result = await model.generateContent(prompt);
			const response = await result.response;
			const text = response.text();
			end_loader();
			var bot_chat = $('#bot_chat').clone();
			bot_chat.find('.direct-chat-text').html(text);
			$('#chat_convo .direct-chat-messages').append(bot_chat.html());
			$("#chat_convo .card-body").animate({ scrollTop: $("#chat_convo .card-body").prop('scrollHeight') }, "fast");
	});

	// ...
</script>
