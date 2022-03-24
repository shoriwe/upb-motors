package test

import (
	"fmt"
	"github.com/shoriwe/upb-motors/internal/data/memory"
	"github.com/shoriwe/upb-motors/internal/web"
	"net"
	"net/http"
)

const (
	testHost = "127.0.0.1:3000"
)

func requestInventory(page int) string {
	return fmt.Sprintf("http://%s/inventory/%d", testHost, page)
}

func serve() net.Listener {
	engine := web.NewEngine(memory.NewMemory())
	l, err := net.Listen("tcp", testHost)
	if err != nil {
		panic(err)
	}
	go http.Serve(l, engine)
	return l
}
