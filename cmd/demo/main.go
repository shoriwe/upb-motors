package main

import (
	"fmt"
	"github.com/shoriwe/upb-motors/internal/data"
	"github.com/shoriwe/upb-motors/internal/data/memory"
	"github.com/shoriwe/upb-motors/internal/logs"
	"github.com/shoriwe/upb-motors/internal/web"
	"log"
	"os"
)

func main() {
	database := memory.NewMemory()
	connection := data.NewConnection(database)
	logger := logs.NewLogger(os.Stderr)
	engine := web.NewEngine(connection, logger)
	executionError := engine.Run(":8080")
	if executionError != nil {
		log.Fatal(executionError)
	}
	fmt.Println("Everything is Fine :)")
}
