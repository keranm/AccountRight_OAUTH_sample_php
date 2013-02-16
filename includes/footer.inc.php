      <!-- begin footer -->
      <hr />

      <div class="footer">
        <p>&copy; MYOB Technology Ltd 2013
        <span class="muted pull-right"><?php // page timer
        // now render the page
          $endtime = microtime();
          $endarray = explode(" ", $endtime);
          $endtime = $endarray[1] + $endarray[0];
          $totaltime = $endtime - $starttime;
          $totaltime = round($totaltime,10);
          echo "This page took: ";
          printf ("%f\n", $totaltime);
          echo " seconds to load" 
        ?></span></p>
      </div>

    </div> <!-- /container -->

    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>