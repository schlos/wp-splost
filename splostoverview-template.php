<?php
/*
Template Name: SPLOST Overview
*/
?>

<?php get_header(); ?>
 <div id="maincontainer" class="overview" >
   <h3>Project Description</h3>
   <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

   					<?php if ( is_front_page() ) { ?>
   						<!-- ><h2><?php the_title(); ?></h2> -->
   					<?php } else { ?>	
   					<!--	<h1><?php the_title(); ?></h1> -->
   					<?php } ?>				

   						<?php the_content(); ?>

   <h3>Quick Stats</h3>
   <div id="stats"></div>
   <h3>Project Locations</h3>
   
    <div id="map" class="fullmap"></div>
    <h3>Category Funding Comparison</h3>
      <p>Below, a funds comparison between this category's projects.</p>
	  <div id="holder"></div>
    <h3>Project Funding Schedule</h3>
    
  <div id="table"></div><!-- end #table -->

                <span class="button wpedit">
              <?php edit_post_link( __( 'Edit', 'twentyten' ), '', '' ); ?></span>

          <?php comments_template( '', true ); ?>

   <?php endwhile; ?>
    </div><!-- end #maincontainer -->
    
    
    <script id="stats" type="text/html">
       <p><span class="statHighlight">{{numberActive}} of {{numberTotalProjects}}</span> projects are active</p>
       <p><span class="statHighlight">{{numberCompletedProjects}}</span> projects are completed</p>
       <p><span class="statHighlight">{{totalSpent}}</span> has been spent as of {{currentDate}} </p>
     </script>
    
   <script id="schedule" type="text/html">
      <table>
      <thead>
      <tr class="tableheader">
      <th>PROJECT</th><th>TOTAL</th><th>2012</th><th>2013</th><th>2014</th><th>2015</th><th>2016</th><th>2017</th><th>2018</th><th>2019</th>
      </tr>
      </thead>
      {{#rows}}
        <tr><td class = "project">{{project}}</td><td class="total">{{total}}</td><td class="yrdolls">{{year2012}}</td><td class="yrdolls">{{year2013}}</td><td class="yrdolls">{{year2014}}</td><td class="yrdolls">{{year2015}}</td><td class="yrdolls">{{year2016}}</td><td class="yrdolls">{{year2017}}</td><td class="yrdolls">{{year2018}}</td><td class="yrdolls">{{year2019}}</td></tr>
      {{/rows}}
      </table>
    </script>
    
    
    <script type="text/javascript">    
      document.addEventListener('DOMContentLoaded', function() {
         loadSpreadsheet(showInfo)
       })    

       function showInfo(data, tabletop) {
               
               
         accounting.settings.currency.precision = 0

         var edProjects = getType(data, "Economic Development")
         var drProjects = getType(data, "Debt Retirement")
         var raProjects = getType(data, "Rec & Cultural Arts")
         var psProjects = getType(data, "Public Safety")

         var map = loadMap()
         data.forEach(function (data){
           displayAddress(map, data)
         })

         function pushBits(element) {
            values.push(parseInt(element.total))
            labels.push(element.project)
            hexcolors.push(element.hexcolor)
          }
              
          var r = Raphael("holder")
          var values = []
          var labels = []
          var hexcolors = []
              data.forEach(pushBits)

                   
          pie = r.piechart(230, 230, 170, values, { 
            legend: labels, 
            legendpos: "east", 
            href: ["#", "#"],
            colors: hexcolors
            })

          pie.hover(function () {
              this.sector.stop();
              this.sector.scale(1.1, 1.1, this.cx, this.cy);
                    

              if (this.label) {
                  this.label[0].stop();
                  this.label[0].attr({ r: 9.5 }); //changed radius of the label's marker
                  this.label[1].attr({ "font-weight": 800 });
              }
          }, function () {
              this.sector.animate({ transform: 's1 1 ' + this.cx + ' ' + this.cy }, 500, "bounce");

              if (this.label) {
                  this.label[0].animate({ r: 5 }, 500, "bounce");
                  this.label[1].attr({ "font-weight": 400 });
              }
          });
          
              
               
          
          
          
         var numberActive = getActiveProjects(edProjects).length
         var numberTotalProjects = 14
         var numberCompletedProjects = completedProjects(edProjects)
         var totalSpent = amountSpent(edProjects)

         var schedule = ich.schedule({
           "rows": turnCurrency(data)
         })

         var stats = ich.stats({
           "numberActive": numberActive,
           "numberTotalProjects": numberTotalProjects,
           "numberCompletedProjects": numberCompletedProjects,
           "totalSpent": accounting.formatMoney(totalSpent),
           "currentDate": getCurrentYear()
         })

         document.getElementById('table').innerHTML = schedule;
         document.getElementById('stats').innerHTML = stats; 

       }
    </script>



<?php get_sidebar(); ?>
<?php get_footer(); ?>
