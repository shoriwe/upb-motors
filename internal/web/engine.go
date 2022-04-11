package web

import (
	"bytes"
	"fmt"
	"github.com/gin-gonic/gin"
	"github.com/shoriwe/upb-motors/internal/data"
	"html/template"
	"io"
	"mime"
	"net/http"
	"os"
	"path"
	"path/filepath"
	"strings"
)

func init() {
	_ = mime.AddExtensionType(".js", "application/javascript")
	_ = mime.AddExtensionType(".min.js", "application/javascript")
	_ = mime.AddExtensionType(".bundle.js", "application/javascript")
	_ = mime.AddExtensionType(".js.map", "application/octet-stream")
	_ = mime.AddExtensionType(".css", "text/css")
	_ = mime.AddExtensionType(".min.css", "text/css")
	_ = mime.AddExtensionType(".css.map", "application/json")
}

type NavigationBar struct {
	Body template.HTML
}

var resources = os.DirFS("internal/web/resources")

const prefix = ""

/*
//go:embed resources/*
var resources embed.FS
const prefix = "resources"
*/

func build(context *gin.Context, t string, object interface{}) string {
	file, openError := resources.Open(filepath.Join(prefix, t))
	if openError != nil {
		panic(openError)
	}
	defer file.Close()
	fileContents, readError := io.ReadAll(file)
	if readError != nil {
		panic(readError)
	}

	var executeError error
	var s string
	if context != nil {
		context.Header("Content-Type", "text/html")
		executeError = template.Must(template.New(t).Parse(string(fileContents))).Execute(context.Writer, object)
	} else {
		buffer := &bytes.Buffer{}
		executeError = template.Must(template.New(t).Parse(string(fileContents))).Execute(buffer, object)
		s = buffer.String()
	}
	if executeError != nil {
		panic(executeError)
	}
	return s
}

func index(context *gin.Context) {
	build(context, "nav-bar.html", NavigationBar{
		Body: template.HTML(build(nil, "index.html", nil)),
	})
}

func static(p string) gin.HandlerFunc {
	return func(context *gin.Context) {
		f := context.Param("filepath")
		extension := f[strings.Index(f, "."):]
		contentType := mime.TypeByExtension(extension)
		fmt.Println(extension, contentType)
		context.Header("Content-Type", contentType)
		context.FileFromFS(path.Join(prefix, p, f), http.FS(resources))
	}
}

func NewEngine(database data.Database) *gin.Engine {
	engine := gin.Default()
	engine.GET(RootLocation, func(context *gin.Context) {
		context.Redirect(http.StatusFound, IndexLocation)
	})
	engine.GET("/css/*filepath", static("css"))
	engine.GET("/js/*filepath", static("js"))
	engine.GET("/static-vendor/*filepath", static("static-vendor"))
	engine.GET(IndexLocation, index)
	return engine
}
