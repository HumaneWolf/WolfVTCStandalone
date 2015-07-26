<html>
<head>
  <title><?php echo $website['name']; ?> </title>
  <style>
  body {
    background-color: #CCB89D;
  }

  .main {
    width: 550px;
    min-height: 250px;
    margin-left: auto;
    margin-right: auto;
    border: solid 2px;
    border-radius: 25px;
    background-color: #DDDDDD;
  }

  .head {
    width: 80%;
    border-bottom: solid 1px;
    margin-left: 10%;
    margin-right: 10%;
    line-height: 50%;
  }

  p, h1 {
    margin-left: 10px;
    margin-right: 10px;
  }
  </style>
</head>
<body>
  <div class="main">
    <div class="head">
      <h1>Website Error</h1>
    </div>
    <p><b><?php echo $website['name']; ?></b> has encountered an unexpected error. You can try to refresh this page in a minute to access the website.</p>
    <p>The website is powered by <a href="https://github.com/HumaneWolf/WolfVTC">WolfVTC</a> by <a href="http://humanewolf.com">HumaneWolf</a>.</p>
  </div>
</body>
</html>