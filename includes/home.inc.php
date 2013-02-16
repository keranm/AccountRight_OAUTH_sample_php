<?php // HOME PAGE CONTENT ?>
<!-- Jumbotron -->
<div class="jumbotron">
  <h1>App Of Awesome</h1>
  <p class="lead">To make full use of the awsesomeness of the cloud, bring together the power of MYOB AccountRight Live and our App of Awesome and watch business financial magic happen.</p>
  <a class="btn btn-large btn-success" href="https://secure.myob.com/oauth2/account/authorize?client_id=<?php echo api_key ?>&redirect_uri=<?php echo urlencode( redirect_url ); ?>&response_type=code&scope=CompanyFile">Click here to link with MYOB AccountRight Live now</a>
</div>

<hr>

<!-- Example row of columns -->
<div class="row-fluid">
  <div class="span4">
    <h2>Awesome</h2>
    <p>This is a sample provided as part of the API Developer Program for MYOB AccountRight Live</p>
    <p><a class="btn" href="#">View details &raquo;</a></p>
  </div>
  <div class="span4">
    <h2>Cloud</h2>
    <p>This sample simply takes  you through the OAuth process to gain access to a users company files. Once you have the appropriate access tokens it then pulls a list of company files.</p>
    <p><a class="btn" href="#">View details &raquo;</a></p>
 </div>
  <div class="span4">
    <h2>Stuff</h2>
    <p>To view content in the company files you'll need the user to provide their company file credentials</p>
    <p><a class="btn" href="#">View details &raquo;</a></p>
  </div>
</div>

