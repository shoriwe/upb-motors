package main

import (
	"github.com/gin-gonic/gin"
	"github.com/shoriwe/upb-motors/internal/api"
	"github.com/shoriwe/upb-motors/internal/cli"
	"github.com/shoriwe/upb-motors/internal/data/sql"
	"log"
	"net"
	"net/http"
	"os"
)

func main() {
	gin.SetMode(gin.ReleaseMode)
	db := sql.NewSQL(os.Getenv(cli.DatabaseURLENV))
	db.APIKey(os.Getenv(cli.APIKeyENV))
	engine := api.NewEngine(db)
	l, err := net.Listen("tcp", os.Getenv(cli.ListenHostENV))
	if err != nil {
		panic(err)
	}
	log.Fatal(http.Serve(l, engine))
}
