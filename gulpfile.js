/*=== Gulp Plugins ===*/
var gulp         = require('gulp');
var sass         = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var cssnano      = require('gulp-cssnano');
var watch        = require('gulp-watch');
var rename       = require('gulp-rename');

/*=== Sass -> Prefix -> Minify ===*/
gulp.task('styles', function () {
    gulp.src('./styles/scss/**/*.scss')

        .pipe(sass().on('error', sass.logError))
        .pipe(cssnano())
        .pipe(gulp.dest('./'))
});

/*=== Watch Styles ===*/
gulp.task('watch', function() {
    gulp.watch('./styles/scss/**/*.scss', ['styles']);
});

/*=== Default Gulp task run watch ===*/
gulp.task('default', ['watch']);
