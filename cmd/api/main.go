package main

import (
	"github.com/shoriwe/upb-motors/internal/data/sql"
	"github.com/shoriwe/upb-motors/internal/web"
	"log"
	"net"
	"net/http"
	"os"
)

func main() {
	engine := web.NewEngine(sql.NewSQL(os.Getenv("DB-URL")))
	l, err := net.Listen("tcp", os.Getenv("HOST"))
	if err != nil {
		panic(err)
	}
	log.Fatal(http.Serve(l, engine))
}
