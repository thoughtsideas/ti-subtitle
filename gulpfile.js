/**
 * TI Subtitle Build File
 */

const del     = require('del');
const gulp    = require('gulp');
const wpPot   = require('gulp-wp-pot');
const sort    = require('gulp-sort');

/**
 * Delete the plugins's .pot before we create a new one.
 */
gulp.task('clean:pot', function () {
  del(['languages/ti-subtitle.pot']);
});

/**
 * Scan the theme and create a POT file.
 *
 * https://www.npmjs.com/package/gulp-wp-pot
 */
gulp.task('wp-pot', ['clean:pot'], function () {
  return gulp.src('*.php')
    .pipe(sort())
    .pipe(wpPot({
      domain: 'ti-subtitle',
      destFile: 'ti-subtitle.pot',
      package: 'TI Subtitle',
      bugReport: 'https://github.com/thoughtsideas/ti-subtitle/issues/',
      lastTranslator: 'Michael Bragg <michael@michaelbragg.net>',
    }))
    .pipe(gulp.dest('languages'));
});

/**
 * Create individual tasks.
 */
gulp.task('i18n', ['wp-pot']);
gulp.task('default', ['i18n']);
