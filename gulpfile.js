var gulp = require('gulp');
var sass = require('gulp-sass');
var sassGlob = require('gulp-sass-glob');
var compass = require('compass-importer');
var autoprefixer = require('gulp-autoprefixer');
var sourcemaps = require('gulp-sourcemaps');
var cssnano = require('gulp-cssnano');

gulp.task('styles', done => {
    gulp.src('./web/sass/screen.scss')
        .pipe(sourcemaps.init())
        .pipe(sassGlob())
        .pipe(sass({importer: compass, outputStyle: 'compact'}).on('error', sass.logError))
        .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
        .pipe(cssnano())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('./web/stylesheets'));
    done();
});

gulp.task('watch', done => {
    gulp.watch('./web/sscss/**/*.scss', gulp.series('styles'));
done();
});

// Default Task
gulp.task('default', gulp.series('styles', 'watch'));