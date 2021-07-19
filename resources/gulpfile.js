const gulp = require('gulp')
const concat = require('gulp-concat')
const errorHandler = require('gulp-error-handle')
const prefix = require('gulp-autoprefixer')
const sass = require('gulp-sass')

function frontSass(done){
  gulp.src(['sass/style.sass', '../blocks/**/*.sass'])
    .pipe(errorHandler())
    .pipe(concat('style.sass'))
    .pipe(sass({outputStyle: process.env.NODE_ENV == 'development' ? 'expanded' : 'compressed'})) 
    .pipe(prefix())
    .pipe(gulp.dest('../public/css/'))
  done()
}

function editorSass(done){
  gulp.src('sass/editor-style.sass')
    .pipe(errorHandler())  
    .pipe(sass({outputStyle: process.env.NODE_ENV == 'development' ? 'expanded' : 'compressed'})) 
    .pipe(prefix())
    .pipe(gulp.dest('../public/css/'))
  done()
}

function adminSass(done){
  gulp.src('sass/admin.sass')
    .pipe(errorHandler())  
    .pipe(sass({outputStyle: process.env.NODE_ENV == 'development' ? 'expanded' : 'compressed'})) 
    .pipe(prefix())
    .pipe(gulp.dest('../public/css/'))
  done()
}

function minifyImages(done){
  gulp.src('images/**/*')
    .pipe(errorHandler())
    .pipe(gulp.dest('../public/images'))
  done()
}

function copyImages(done){
  gulp.src('images/**/*')
    .pipe(errorHandler())
    .pipe(gulp.dest('../public/images'))
  done()
}

function watch(done){
  gulp.watch(['../blocks/**/*.sass', 'sass/**/*.sass', '!sass/editor-style.sass', '!sass/admin.sass'], frontSass)
  gulp.watch(['sass/**/*.sass', 'sass/editor-style.sass', '!sass/admin.sass'], editorSass)
  gulp.watch('sass/admin.sass', adminSass)
  gulp.watch('images/**/*', copyImages)

  //only update the 1 file that changed
  gulp.watch('images/**/*').on('all', (event, file) => {
    gulp.src(file)
      .pipe(errorHandler())
      .pipe(gulp.dest('../public/images'))
  })

  done()
}

exports.prod = gulp.parallel(frontSass, editorSass, adminSass, minifyImages)
exports.watch = watch
exports.default = watch