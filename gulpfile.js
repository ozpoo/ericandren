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

gulp.task('js', function () {
	gulp.src('assets/js/src/script.js')
	.pipe(jshint())
	.pipe(jshint.reporter('fail'))
	.pipe(concat('build.js'))
	.pipe(uglify())
	.pipe(gulp.dest('assets/js/build'));
});

gulp.task('scss', function () {
  gulp.src('assets/css/src/style.scss')
	.pipe(sassLint())
  .pipe(sassLint.format())
  .pipe(sassLint.failOnError())
	.pipe(postcss([ cssdeclsort({order: 'smacss'}) ]))
	.pipe(gulp.dest('assets/css/src'))
	.pipe(sass().on('error', sass.logError))
  .pipe(postcss([ autoprefixer() ]))
  .pipe(concat('build.css'))
	.pipe(minifyCss())
  .pipe(gulp.dest('assets/css/build'));
});

gulp.task('watch', function () {
  gulp.watch(['assets/css/src/style.scss'], ['scss']);
	gulp.watch(['assets/js/src/script.js'], ['js']);
});
