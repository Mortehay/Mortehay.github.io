module.exports = function(grunt) {

grunt.initConfig({
concat: {
  js: {
    options: {
      separator: ';'
    },
    src: [
      'js/working/*.js'
    ],
    dest: 'js/scripts.js'
  },
},

uglify: {
  options: {
    mangle: false
  },
  js: {
    files: {
      'js/scripts.min.js': ['js/scripts.js']
    }
  }
},

sass: {
  style: {
    files: {
      "css/main.css": "css/main.scss"
    },
  }
},

cssmin: {
  target: {
    files: [{
      expand: true,
      cwd: 'css',
      src: ['main.css', '!main.min.css'],
      dest: 'css',
      ext: '.min.css'
    }]
  }
},

watch: {
    js: {
      files: ['js/working/*.js'],
      tasks: ['concat:js', 'uglify:js'],
      options: {
          livereload: 35729
      }
    },
    css: {
      files: ['css/*.scss'],
      tasks: ['sass:style'],
      options: {
          livereload: 35729
      }
    },
    cssmin: {
      files: ['css/main.css'],
      tasks: ['cssmin:target'],
      options: {
          livereload: 35729
      }
    },
    
    html: {
      files: ['*.html'],
      options: {
        livereload: 35729
      }
    },

    php : {
      files : ['**/*.php'],
      options : {
        livereload : 35729
        } 
    }
  }
});



  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.registerTask('default', ['watch']);
};