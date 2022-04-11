package test

import (
	"encoding/json"
	"github.com/shoriwe/upb-motors/internal/api"
	"github.com/shoriwe/upb-motors/internal/data/memory"
	"io"
	"net/http"
	"testing"
)

func TestMemoryQueryInventoryValidPage(t *testing.T) {
	l := serve()
	defer l.Close()
	response, err := http.Get(requestInventory(1))
	if err != nil {
		t.Fatal(err)
	}
	contents, readError := io.ReadAll(response.Body)
	if readError != nil {
		t.Fatal(readError)
	}
	var jsonResponse api.Response
	parseError := json.Unmarshal(contents, &jsonResponse)
	if parseError != nil {
		t.Fatal(parseError)
	}
	if !jsonResponse.Succeed {
		t.Fatal(jsonResponse.Body)
	}
	if len(jsonResponse.Body) != memory.PageLength {
		t.Fatalf("expecting %d", memory.PageLength)
	}
}

func TestMemoryQueryInventoryInvalidPage(t *testing.T) {
	l := serve()
	defer l.Close()
	response, err := http.Get(requestInventory(0))
	if err != nil {
		t.Fatal(err)
	}
	contents, readError := io.ReadAll(response.Body)
	if readError != nil {
		t.Fatal(readError)
	}
	var jsonResponse api.Response
	parseError := json.Unmarshal(contents, &jsonResponse)
	if parseError != nil {
		t.Fatal(parseError)
	}
	if !jsonResponse.Succeed {
		t.Fatal(jsonResponse.Body)
	}
	if jsonResponse.Body != nil {
		t.Fatal("expecting empty")
	}
}
