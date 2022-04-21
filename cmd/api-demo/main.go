package main

import (
	"github.com/gin-gonic/gin"
	"github.com/shoriwe/upb-motors/internal/api"
	"github.com/shoriwe/upb-motors/internal/data/memory"
	"log"
	"net"
	"net/http"
)

func main() {
	gin.SetMode(gin.ReleaseMode)
	db := memory.NewMemory()
	db.APIKey("api-demo")
	engine := api.NewEngine(db)
	l, err := net.Listen("tcp", "127.0.0.1:8000")
	if err != nil {
		panic(err)
	}
	log.Fatal(http.Serve(l, engine))
}
