const gulp = require('gulp');
const uglify = require("gulp-uglify");
const jshint = require("gulp-jshint");
const sass = require('gulp-sass');
const sassLint = require('gulp-sass-lint');
const minifyCss = require("gulp-minify-css");
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssdeclsort = require('css-declaration-sorter');
const concat = require("gulp-concat");

var watchJS = true;
var watchSCSS = true;

gulp.task('js', function () {
	if(watchJS) {
		watchJS = false;
		gulp.src('assets/js/src/script.js')
		.pipe(jshint())
		.pipe(jshint.reporter('fail'))
		.pipe(concat('build.js'))
		.pipe(uglify())
		.pipe(gulp.dest('assets/js/build'));
		watchJS = true;
	}
});

gulp.task('scss', function () {
	if(watchSCSS) {
		watchSCSS = false;
	  gulp.src('assets/css/src/style.scss')
		.pipe(sassLint())
	  .pipe(sassLint.format())
	  .pipe(sassLint.failOnError())
		.pipe(postcss([ cssdeclsort({order: 'smacss'}) ]))
		.pipe(gulp.dest('./'))
		.pipe(sass().on('error', sass.logError))
	  .pipe(postcss([ autoprefixer() ]))
	  .pipe(concat('build.css'))
		.pipe(minifyCss())
	  .pipe(gulp.dest('assets/css/build'));
		watchSCSS = true;
	}
});

gulp.task('watch', function () {
  gulp.watch(['assets/css/src/style.scss'], ['scss']);
	gulp.watch(['assets/js/src/script.js'], ['js']);
});
