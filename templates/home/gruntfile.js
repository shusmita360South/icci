module.exports = function (grunt) {
  'use strict';

  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-autoprefixer');

  var configBridge = grunt.file.readJSON('./configBridge.json', { encoding: 'utf8' });

  // to do
  var globalConfig = {
    public_html: '/',
    assets: 'assets',
    source: 'source',
    stylesheet: 'styles',
    javascript: 'app',
  };


  // Project configuration.
  grunt.initConfig({
    // Config Variable
    globalConfig: globalConfig,
    sass: {
      dist: {
        files: {
          '<%= globalConfig.assets %>/css/<%= globalConfig.stylesheet %>.css': '<%= globalConfig.source %>/scss/<%= globalConfig.stylesheet %>.scss'
        }
      }
    },
    autoprefixer: {
      options: {
        browsers: configBridge.config.autoprefixerBrowsers
      },
      development: {
        options: {
          map: true
        },
        src: '<%= globalConfig.assets %>/css/*.css'
      },
    },
    cssmin: {
      options: {
        compatibility: 'ie11',
        keepSpecialComments: '*',
        sourceMap: false,
        advanced: false
      },
      cssvendor: {
        src: '<%= globalConfig.assets %>/css/vendor.css',
        dest: '<%= globalConfig.assets %>/css/vendor.min.css'
      },
      cssstyle: {
        src: '<%= globalConfig.assets %>/css/<%= globalConfig.stylesheet %>.css',
        dest: '<%= globalConfig.assets %>/css/<%= globalConfig.stylesheet %>.min.css'
      },
    },
    concat: {
      cssvendor: {
        src: [
          '<%= globalConfig.source %>/scss/vendor/*.css'
        ],
        dest: '<%= globalConfig.assets %>/css/vendor.css'
      },
      jsvendor: {
        src: [
          '<%= globalConfig.source %>/js/vendor/*.js'
        ],
        dest: '<%= globalConfig.assets %>/js/vendor.js'
      },
      app: {
        src: [
          '<%= globalConfig.source %>/js/<%= globalConfig.javascript %>.js'
        ],
        dest: '<%= globalConfig.assets %>/js/<%= globalConfig.javascript %>.js'
      }
    },
    uglify: {
      options: {
        compress: {
          warnings: false
        },
        mangle: true,
        preserveComments: false
      },
      vendor: {
        src: '<%= concat.jsvendor.dest %>',
        dest: '<%= globalConfig.assets %>/js/vendor.min.js'
      },
      app: {
        src: '<%= globalConfig.assets %>/js/<%= globalConfig.javascript %>.js',
        dest: '<%= globalConfig.assets %>/js/<%= globalConfig.javascript %>.min.js'
      },
    },
    watch: {
      css: {
        files: ['<%= globalConfig.source %>/scss/*.scss', '<%= globalConfig.source %>/scss/*/*.scss'],
        tasks: ['css']
      },
      javascript: {
        files: ['<%= globalConfig.source %>/js/vendor/*.js', '<%= globalConfig.source %>/js/<%= globalConfig.javascript %>.js'],
        tasks: ['js']
      },
    },
  });

  grunt.registerTask('js',  ['concat', 'uglify']);
  grunt.registerTask('css', ['sass', 'concat', 'autoprefixer', 'cssmin']);

  //grunt.registerTask('default', ['js', 'css']);
};