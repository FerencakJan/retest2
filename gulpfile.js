"use strict";

// nastavení
let settings = {
  browsersync: {
    url: "http://localhost/",
    browser: "canary",
    watch: [
      "templates/**/*.html",
      "templates/**/*.php",
      "templates/**/*.html.twig",
    ],
  },
  css: {
    source: "assets/css/styles.scss",
    target: "public/build/css/",
    filename: "styles.css",
    watch: [
      "assets/css/**/*.scss",
      "assets/css/**/*.css",
      "!assets/css/styles.css",
    ],
    components: [
      "assets/css/base/**/*.scss",
      "!assets/css/base/print.scss",
      "!assets/css/base/variables.scss",
      "assets/css/components/**/*.scss",
    ],
    componentsStyleLint: [
      "assets/css/base/**/*.scss",
      "!assets/css/base/print.scss",
      "!assets/css/base/variables.scss",
      "assets/css/components/**/*.scss",
      "!assets/css/base/libs_used/*.scss",
    ],
  },
  js: {
    source: [
      "assets/js/libs-used/**/*.js",
      "assets/js/components/**/*.js",
      "assets/js/main.js",
    ],
    target: "public/build/js/",
    filename: "scripts.js",
    watch: ["assets/js/**/*.js", "!assets/js/scripts.js"],
    components: ["assets/js/components/**/*.js", "assets/js/main.js"],
  },
  img: {
    source: "assets/img/**/*.{gif,jpg,jpeg,png,webp}",
    target: "public/build/img",
    watch: ["assets/img/*", "!assets/img/icon-*.svg"],
  },
  icons: {
    source: ["assets/img/icon-*.svg", "assets/img/icons/*.svg"],
    sourceIcons: "public/build/img/icons/*.svg",
    target: "public/build/img/",
    filename: "symbol/icons.svg",
    style: "../../../assets/css/icons/icons.scss",
    preview: "../../page-components/icons.php",
    prettycode: false,
  },
};

// gulp
let gulp = require("gulp");
// spojení souborů
let concat = require("gulp-concat");
// Cheerio - manipulace v HTML/XML souborech
let cheerio = require("cheerio");
// plumber - odchycení chybových hlášek
let plumber = require("gulp-plumber");
// přejmenování souborů
let rename = require("gulp-rename");
// sourcemaps - generování map zdrojů
let sourcemaps = require("gulp-sourcemaps");
// through2 - Node wrapper
let through2 = require("through2");
// Vinyl - konvertor streamu
let Vinyl = require("vinyl");
// BrowserSync - live realod, server, ovládání prohlížeče
let browserSync = require("browser-sync");
// SASS - generování CSS z preprocesoru
let sass = require("gulp-sass")(require("sass"));
let sassGlob = require("gulp-sass-glob");
// postCSS - postprocessing CSS (minifikace, autoprefixer...)
let postcss = require("gulp-postcss");
let autoprefixer = require("autoprefixer");
let cssnano = require("cssnano");
let flexbugs = require("postcss-flexbugs-fixes");
let pxtorem = require("postcss-pxtorem");
// CSScomb - uhlazení SASS souborů (řazení vlastností, odsazení...)
let csscomb = require("gulp-csscomb");
// lintování CSS
let stylelint = require("gulp-stylelint");
// minifikace JavaScriptu
let uglify = require("gulp-uglify-es").default;
// lintování JavaScriptu
let jshint = require("gulp-jshint");
// Prettier - uhlazení JS souborů
let prettier = require("gulp-prettier");
// Imagemin - optimalizace obrázků
let imagemin = require("gulp-imagemin");
// minimalizace SVG
let svgmin = require("gulp-svgmin");
let svgSprite = require("gulp-svg-sprite");
let replace = require("gulp-replace");

let basename = require("path").basename;

// postCSS pluginy a nastavení
let postcssPlugins = [flexbugs(), pxtorem(), autoprefixer(), cssnano()];

// výpis chybových hlášek
let onError = function (err) {
  console.log(err);
  this.emit("end");
};

// SASS kompilace
function wtSass() {
  return gulp
    .src(settings.css.source)
    .pipe(plumber({ errorHandler: onError }))
    .pipe(sourcemaps.init())
    .pipe(sassGlob())
    .pipe(sass())
    .pipe(rename(settings.css.filename))
    .pipe(sourcemaps.write("."))
    .pipe(gulp.dest(settings.css.target))
    .pipe(browserSync.stream());
}

// CSS kompilace (produkce)
function wtCss() {
  return gulp
    .src(settings.css.source)
    .pipe(plumber({ errorHandler: onError }))
    .pipe(sourcemaps.init())
    .pipe(sassGlob())
    .pipe(sass({ style: "expanded" }))
    .pipe(postcss(postcssPlugins))
    .pipe(rename(settings.css.filename))
    .pipe(sourcemaps.write("."))
    .pipe(gulp.dest(settings.css.target));
}

// CSScomb - úpravy SASS souborů (řazení vlastností, odsazení...)
function wtCssComb() {
  return gulp
    .src(settings.css.componentsStyleLint, { base: "./" })
    .pipe(plumber({ errorHandler: onError }))
    .pipe(csscomb())
    .pipe(gulp.dest("./"));
}

// CSS - lintování (Stylelint)
function wtStyleLint() {
  return gulp
    .src(settings.css.components, { base: "./" })
    .pipe(plumber({ errorHandler: onError }))
    .pipe(
      stylelint({
        reporters: [
          {
            formatter: "string",
            console: true,
          },
        ],
      })
    );
}

// JavaScript - spojení souborů
function wtConcatJs() {
  return gulp
    .src(settings.js.source, { base: "./" })
    .pipe(plumber({ errorHandler: onError }))
    .pipe(sourcemaps.init())
    .pipe(concat(settings.js.target + settings.js.filename))
    .pipe(sourcemaps.write("."))
    .pipe(gulp.dest("./"));
}

// JavaScript - spojení a minifikace (produkce)
function wtJs() {
  return gulp
    .src(settings.js.source, { base: "./" })
    .pipe(plumber({ errorHandler: onError }))
    .pipe(sourcemaps.init())
    .pipe(concat(settings.js.target + settings.js.filename))
    .pipe(uglify())
    .pipe(sourcemaps.write("."))
    .pipe(gulp.dest("./"));
}

// JavaScript - lintování
function wtJsLint() {
  return gulp
    .src(settings.js.components)
    .pipe(plumber({ errorHandler: onError }))
    .pipe(jshint())
    .pipe(jshint.reporter("default"))
    .pipe(jshint.reporter("fail"));
}

// Prettier - uhlazení JS souborů
function wtPrettier() {
  return gulp
    .src(settings.js.components, { base: "./" })
    .pipe(plumber({ errorHandler: onError }))
    .pipe(prettier({ singleQuote: true }))
    .pipe(gulp.dest("./"));
}

// optimalizace obrázků
function wtImages() {
  return gulp
    .src(settings.img.source)
    .pipe(plumber({ errorHandler: onError }))
    .pipe(
      imagemin({
        interlaced: true,
        pngquant: true,
        progressive: true,
      })
    )
    .pipe(gulp.dest(settings.img.target));
}

function wtSvgIconsBefore() {
  return gulp
    .src(settings.icons.source)
    .pipe(
      through2.obj(function (file, encoding, callback) {
        let $ = cheerio.load(file.contents.toString(), { xmlMode: true });
        let $svg = $("svg");

        if (!$svg.attr("viewBox")) {
          let fWidth = parseFloat($svg.attr("width"));
          let fHeight = parseFloat($svg.attr("height"));

          $svg.attr("viewBox", "0 0 " + fWidth + " " + fHeight);
        }

        if ($svg.attr("fill") && $svg.attr("fill") !== "none") {
          $svg.attr("fill", "currentColor");
        }
        if ($svg.attr("stroke") && $svg.attr("stroke") !== "none") {
          $svg.attr("stroke", "currentColor");
        }

        let sFilename = basename(file.path, ".svg");

        this.push(
          new Vinyl({
            path: "./icons/" + sFilename + ".svg",
            contents: Buffer.from($.html()),
          })
        );

        callback();
      })
    )
    .pipe(
      svgmin(function getOptions(file) {
        let prefix = basename(file.path, ".svg");
        return {
          plugins: [
            {
              cleanupIDs: {
                prefix: prefix + "-",
                minify: true,
              },
            },
          ],
        };
      })
    )
    .pipe(gulp.dest(settings.icons.target));
}

// generování SVG sprite ikon
function wtSvgIcons() {
  return (
    gulp
      .src(settings.icons.sourceIcons)
      .pipe(plumber({ errorHandler: onError }))
      // .pipe(svgstore({
      //     inlineSvg: true,
      // }))
      .pipe(replace("&gt;", ">"))
      .pipe(
        svgSprite({
          mode: {
            symbol: {
              sprite: "icons.svg",
            },
          },
        })
      )
      .pipe(
        through2.obj(function (file, encoding, cb) {
          let $ = cheerio.load(file.contents.toString(), { xmlMode: true });

          // odstraní fill atributy u souborů, které nemají v názvu color
          // $('symbol').not('[id*="color"]').find('*').removeAttr('fill').removeAttr('stroke');

          // Přepíše fill nebo stroke u souborů, které nemají v názvu color
          let symbolItemNotColor = $("symbol").not('[id*="color"]');
          for (let i = 0; i < symbolItemNotColor.length; i++) {
            for (let j = 0; j < symbolItemNotColor[i].children.length; j++) {
              if (
                symbolItemNotColor[i].children[j].attribs.stroke &&
                symbolItemNotColor[i].children[j].attribs.stroke !== "none"
              ) {
                symbolItemNotColor[i].children[j].attribs.stroke =
                  "currentColor";
              }
              if (
                symbolItemNotColor[i].children[j].attribs.fill &&
                symbolItemNotColor[i].children[j].attribs.fill !== "none"
              ) {
                symbolItemNotColor[i].children[j].attribs.fill = "currentColor";
              }
            }
          }

          // odstraní style tagy
          $("[style]").removeAttr("style");

          // vytáhneme si název, výšku a šířku
          let data = $("svg > symbol")
            .map(function () {
              let $this = $(this);
              let name = $this.attr("id");

              let viewBox = $this.attr("viewBox").split(" ");

              return {
                name: name,
                width: viewBox[2],
                height: viewBox[3],
              };
            })
            .get();

          // převedeme na SASS formát
          let dataToStyles = "";
          for (let i = 0; i < data.length; i++) {
            dataToStyles =
              dataToStyles + "\n.icon--" + data[i].name + " {" + "\n";
            dataToStyles =
              dataToStyles + "  width: " + data[i].width + "px;\n\n";
            dataToStyles = dataToStyles + "  &:before {" + "\n";
            dataToStyles =
              dataToStyles +
              "    padding-top: calc(" +
              data[i].height +
              " / " +
              data[i].width +
              ") * 100%;" +
              "\n";
            dataToStyles = dataToStyles + "  }" + "\n";
            dataToStyles = dataToStyles + "}" + "\n";
          }

          // uložíme do soubou
          let fileSASS = new Vinyl({
            path: settings.icons.style,
            contents: new Buffer.from(dataToStyles),
          });

          // vygenerujeme náhledový HTML soubor
          let dataToPreview = "";
          // dataToPreview = dataToPreview + '<!DOCTYPE html><html lang="cs"><head><meta charset="utf-8"><title>SVG preview</title><link rel="stylesheet" href="/public/build/css/styles.css"></head><body>' + '\n'
          for (let i = 0; i < data.length; i++) {
            dataToPreview =
              dataToPreview +
              '<div style="padding:5px;margin:5px;display:inline-block;border:1px dotted gray;">' +
              "\n";
            dataToPreview =
              dataToPreview +
              '<span class="icon icon--' +
              data[i].name +
              '">' +
              "\n";
            dataToPreview =
              dataToPreview +
              '  <svg class="icon__svg"><use xlink:href="#' +
              data[i].name +
              '"></use></svg>' +
              "\n";
            dataToPreview = dataToPreview + "</span>" + "\n";
            dataToPreview = dataToPreview + "</div>" + "\n";
          }
          // dataToPreview = dataToPreview + '</body>' + '\n';

          // uložíme do soubou
          let fileHTML = new Vinyl({
            path: settings.icons.preview,
            contents: new Buffer.from(dataToPreview),
          });

          file.contents = new Buffer.from($.xml());
          this.push(fileSASS);
          this.push(fileHTML);
          this.push(file);
          cb();
        })
      )
      .pipe(gulp.dest(settings.icons.target))
  );
}

// optimalizace SVG sprite
function wtSvgOptimizeSprite() {
  return gulp
    .src(settings.icons.target + settings.icons.filename, { base: "./" })
    .pipe(plumber({ errorHandler: onError }))
    .pipe(
      svgmin({
        plugins: [
          { removeUselessDefs: false },
          // {removeXMLProcInst: false},
          { removeDoctype: false },
          { removeTitle: false },
          { cleanupIDs: false },
          { removeViewBox: false },
        ],
        js2svg: { pretty: settings.icons.prettycode },
      })
    )
    .pipe(gulp.dest("./"));
}

function wtSvgOptimize() {
  return gulp
    .src(["assets/img/*.svg", "!assets/img/icon-*.svg"])
    .pipe(plumber({ errorHandler: onError }))

    .pipe(
      svgmin(function getOptions(file) {
        let prefix = basename(file.path, ".svg");
        return {
          plugins: [
            {
              cleanupIDs: {
                prefix: prefix + "-",
                minify: true,
              },
              removeViewBox: false,
            },
          ],
          js2svg: { pretty: settings.icons.prettycode },
        };
      })
    )
    .pipe(gulp.dest(settings.icons.target));
}

// sledování změn souborů
function wtWatch(cb) {
  // nastavení BrowserSync:
  browserSync.init({
    proxy: settings.browsersync.url,
    browser: settings.browsersync.browser,
  });

  gulp
    .watch(
      settings.icons.source,
      gulp.series(wtSvgIconsBefore, wtSvgIcons, wtSvgOptimize)
    )
    .on("change", browserSync.reload);
  gulp.watch(settings.css.watch, wtSass);
  gulp.watch(settings.img.watch, gulp.series(wtImages, wtSvgOptimize));
  gulp.watch(settings.js.watch, wtConcatJs).on("change", browserSync.reload);
  gulp.watch(settings.browsersync.watch).on("change", browserSync.reload);

  cb();
}

// aliasy tasků
// defaultni task
exports.code = gulp.series(
  gulp.parallel(
    gulp.series(wtCssComb, wtCss, wtStyleLint),
    gulp.series(wtPrettier, wtConcatJs, wtJs, wtJsLint),
    wtImages,
    gulp.series(
      wtSvgIconsBefore,
      wtSvgIcons,
      wtSvgOptimize,
      wtSvgOptimizeSprite
    )
  ),
  wtWatch
);

// generování CSS
exports.makecss = gulp.series(wtCssComb, wtCss);

// generování JavaScriptu
exports.makejs = gulp.series(wtPrettier, wtConcatJs, wtJs);

// generování ikon + optimalizace
exports.makeicons = gulp.series(
  wtSvgIconsBefore,
  wtSvgIcons,
  wtSvgOptimize,
  wtSvgOptimizeSprite
);

// úpravy před nahráním do produkce
exports.deploy = gulp.parallel(
  gulp.series(wtCss),
  gulp.series(wtJs),
  wtImages,
  gulp.series(wtSvgIconsBefore, wtSvgIcons, wtSvgOptimize, wtSvgOptimizeSprite)
);
exports.default = gulp.parallel(
  gulp.series(wtCss),
  gulp.series(wtJs),
  wtImages,
  gulp.series(wtSvgIconsBefore, wtSvgIcons, wtSvgOptimize, wtSvgOptimizeSprite)
);

exports.commit = gulp.parallel(
  gulp.series(wtCssComb, wtCss, wtStyleLint),
  gulp.series(wtPrettier, wtConcatJs, wtJs, wtJsLint),
  gulp.series(wtSvgIconsBefore, wtSvgIcons, wtSvgOptimize, wtSvgOptimizeSprite)
);

