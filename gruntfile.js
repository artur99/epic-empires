module.exports = function(grunt) {
	grunt.initConfig({
		bower_concat: {
			basic: {
				dest: {
					js: 'bower_components/bowercomp.js',
					css: 'bower_components/bowercomp.css'
				}
			}
		},
		copy: {
		  main: {
		    files: [
				{
					expand: true,
					cwd: 'bower_components/Materialize/font/',
					src: '**',
					dest: 'public_html/assets/fonts/',
				},
				{
					expand: true,
					cwd: 'bower_components/font-awesome/fonts/',
					src: '**',
					dest: 'public_html/assets/fonts/',
				},
				{
					expand: true,
					cwd: 'bower_components/country-flags/images/',
					src: '**',
					dest: 'public_html/assets/images/',
				}
		    ],
		  },
		},
		less: {
	      development: {
	        options: {
	          compress: true,
	          yuicompress: true,
	          optimization: 2
	        },
	        files: [{
                expand: true,        // Enable dynamic expansion.
                cwd: 'src/less',  // Src matches are relative to this path.
                src: ['*.less'],     // Actual pattern(s) to match.
                dest: 'src/less/compiled',  // Destination path prefix.
                ext: '.css',         // Dest filepaths will have this extension.
	        }]
	      }
	    },
		concat: {
		    dist: {
		      files: [
		        {src: ['bower_components/bowercomp.js', 'src/js/lib.js', 'src/js/*.js', '!src/js/init.js', 'src/js/init.js'], dest: 'public_html/assets/components/data.js'},
				{src: ['bower_components/bowercomp.css', 'src/less/compiled/fixer.css', 'src/less/compiled/globals.css', 'src/less/compiled/elements.css', 'src/less/compiled/*.css', 'src/less/compiled/main.css'], dest: 'public_html/assets/components/data.css'}
		      ],
		    },
		},
		clean: ['bower_components/bowercomp.js', 'bower_components/bowercomp.css', 'src/less/compiled'],
		uglify: {
			options: {
			  mangle: false,
			  preserveComments: 'some'
			},
			my_target: {
			  files: {
			    'public_html/assets/components/data.js': ['public_html/assets/components/data.js']
			  }
			}
		},
		cssmin: {
		  options: {
		    shorthandCompacting: false,
		    roundingPrecision: -1
		  },
		  target: {
		    files: {
		      'public_html/assets/components/data.css': ['public_html/assets/components/data.css']
		    }
		  }
	  	},
		watch: {
		    scripts: {
		        files: ['src/js/*.js', 'src/less/*.less'],
		        tasks: ['dev-watcher'],
		        options: {
		            interrupt: true
		        }
		    }
		}
	});
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-bower-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.registerTask('default', ['bower_concat', 'less', 'concat', 'copy', 'uglify', 'cssmin', 'clean']);
	grunt.registerTask('prep', ['bower_concat', 'less', 'concat', 'copy']);
	grunt.registerTask('dev-watcher', ['less', 'concat']);
};
