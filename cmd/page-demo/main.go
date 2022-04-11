package main

import (
	"github.com/gin-gonic/gin"
	"github.com/shoriwe/upb-motors/internal/data/memory"
	"github.com/shoriwe/upb-motors/internal/web"
	"log"
)

func main() {
	gin.SetMode(gin.ReleaseMode)
	database := memory.NewMemory()
	engine := web.NewEngine(database)
	log.Fatal(engine.Run("127.0.0.1:8000"))
}
