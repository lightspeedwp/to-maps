const gulp         = require('gulp');
const gettext      = require('gulp-gettext');
const jshint       = require('gulp-jshint');
const plumber      = require('gulp-plumber');
const rename       = require('gulp-rename');
const sort         = require('gulp-sort');
const uglify       = require('gulp-uglify');
const wppot        = require('gulp-wp-pot');

const browserlist  = ['last 2 version', '> 1%'];

gulp.task('default', function() {
	console.log('Use the following commands');
	console.log('--------------------------');
	console.log('gulp compile-js     to compile the js to min.js');
	console.log('gulp watch          to continue watching the files for changes');
	console.log('gulp wordpress-lang to compile the to-maps.pot, to-maps-en_EN.po and to-maps-en_EN.mo');
});

gulp.task('js', function() {
	return gulp.src('assets/js/to-maps.js')
		.pipe(plumber({
			errorHandler: function(err) {
				console.log(err);
				this.emit('end');
			}
		}))
		.pipe(jshint())
		.pipe(uglify())
		.pipe(rename({
			suffix: '.min'
		}))
		.pipe(gulp.dest('assets/js'))
});

gulp.task('compile-js', (['js']));

gulp.task('watch-js', function () {
	return gulp.watch('assets/js/src/**/*.js', ['compile-js']);
});

gulp.task('watch', ['watch-js']);

gulp.task('wordpress-pot', function() {
	return gulp.src('**/*.php')
		.pipe(sort())
		.pipe(wppot({
			domain: 'to-maps',
			package: 'to-maps',
			bugReport: 'https://github.com/lightspeeddevelopment/to-maps/issues',
			team: 'LightSpeed <webmaster@lsdev.biz>'
		}))
		.pipe(gulp.dest('languages/to-maps.pot'))
});

gulp.task('wordpress-po', function() {
	return gulp.src('**/*.php')
		.pipe(sort())
		.pipe(wppot({
			domain: 'to-maps',
			package: 'to-maps',
			bugReport: 'https://www.lsdev.biz/product/to-maps-maps/issues',
			team: 'LightSpeed <webmaster@lsdev.biz>'
		}))
		.pipe(gulp.dest('languages/to-maps-en_EN.po'))
});

gulp.task('wordpress-po-mo', ['wordpress-po'], function() {
	return gulp.src('languages/to-maps-en_EN.po')
		.pipe(gettext())
		.pipe(gulp.dest('languages'))
});

gulp.task('wordpress-lang', (['wordpress-pot', 'wordpress-po-mo']));
