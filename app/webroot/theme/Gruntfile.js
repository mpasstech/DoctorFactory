module.exports = function(grunt) {
    grunt.initConfig({
		sass: {
			options: {
                includePaths: ['node_modules/bootstrap-sass/theme/stylesheets']
            },
            dist: {
				options: {
					outputStyle: 'compressed'
				},
                files: [{
                    'theme/css/main.css': 'theme/scss/main.scss',  							/* 'All main SCSS' */
                    'theme/css/color_skins.css': 'theme/scss/color_skins.scss', 				/* 'All Color Option' */
                    'theme/css/timeline.css': 'theme/scss/pages/timeline.scss', 				/* 'Timeline SCSS to CSS' */
                    'theme/css/blog.css': 'theme/scss/pages/blog.scss', 						/* 'Blog page' */
                    'theme/css/chatapp.css': 'theme/scss/pages/chatapp.scss', 				/* 'Chat App Page SCSS to CSS' */
                    'theme/css/authentication.css': 'theme/scss/pages/authentication.scss', 	/* 'Authentication Page SCSS to CSS' */
                    'theme/css/inbox.css': 'theme/scss/pages/inbox.scss', 					/* 'Email App' */
				}]
            }
        },
        uglify: {
          my_target: {
            files: {
                'theme/bundles/libscripts.bundle.js': ['theme/plugins/jquery/jquery-v3.2.1.min.js','theme/plugins/bootstrap/js/bootstrap.js'], /* main js*/
                'theme/bundles/vendorscripts.bundle.js':
                ['theme/plugins/bootstrap-select/js/bootstrap-select.js','theme/plugins/jquery-slimscroll/jquery.slimscroll.js','theme/plugins/node-waves/waves.js','theme/plugins/fullscreen/screenfull.js'], /* coman js*/
                'theme/bundles/mainscripts.bundle.js':
                ['theme/js/admin.js','theme/js/demo.js','theme/js/fullscreen.js'], /*coman js*/
				
                'theme/bundles/morrisscripts.bundle.js': ['theme/plugins/raphael/raphael.min.js','theme/plugins/morrisjs/morris.js'], /* Morris Plugin Js */
                'theme/bundles/flotscripts.bundle.js': ['theme/plugins/flot-charts/jquery.flot.js','theme/plugins/flot-charts/jquery.flot.resize.js','theme/plugins/flot-charts/jquery.flot.pie.js','theme/plugins/flot-charts/jquery.flot.categories.js','theme/plugins/flot-charts/jquery.flot.time.js'], /* Flot Chart js*/
                'theme/bundles/chartscripts.bundle.js': ['theme/plugins/chartjs/Chart.bundle.js'], /* ChartJs js*/
                'theme/bundles/jvectormap.bundle.js': ['theme/plugins/jvectormap/jquery-jvectormap-2.0.3.min.js','theme/plugins/jvectormap/jquery-jvectormap-world-mill-en.js'], /* ChartJs js*/
                'theme/bundles/sparkline.bundle.js': ['theme/plugins/jquery-sparkline/jquery.sparkline.js'], /* sparkline js*/
                'theme/bundles/countTo.bundle.js': ['theme/plugins/jquery-countto/jquery.countTo.js'], /* CountTo js*/
                'theme/bundles/knob.bundle.js': ['theme/plugins/jquery-knob/jquery.knob.min.js'], /* knob js*/
                'theme/bundles/fullcalendarscripts.bundle.js': ['theme/plugins/fullcalendar/lib/moment.min.js','theme/plugins/fullcalendar/lib/jquery-ui.min.js','theme/plugins/fullcalendar/fullcalendar.min.js'],   /* calender page js */
                'theme/bundles/datatablescripts.bundle.js': ['theme/plugins/jquery-datatable/jquery.dataTables.min.js','theme/plugins/jquery-datatable/dataTables.bootstrap4.min.js'], /* Jquery DataTable Plugin Js  */
                'theme/bundles/footable.bundle.js': ['theme/plugins/footable-bootstrap/js/footable.min.js'], /* knob js*/
              }
            }
        }                
    });
    grunt.loadNpmTasks("grunt-sass");
    grunt.loadNpmTasks('grunt-contrib-uglify');
    
    grunt.registerTask("buildcss", ["sass"]);	
    grunt.registerTask("buildjs", ["uglify"]);
};


