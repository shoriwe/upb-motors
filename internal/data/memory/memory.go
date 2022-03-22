package memory

import (
	"github.com/shoriwe/upb-motors/internal/data"
	"github.com/shoriwe/upb-motors/internal/data/objects"
	"math/rand"
)

const (
	PageLength = 10
)

var (
	dependencies = []string{"Bucaramanga", "Bogota", "Giron"}
	descriptions = []string{"Mazda", "Nissan", "Ford"}
	names        = []string{"sedan", "camioneta", "deportivo"}
)

type Memory struct {
	inventory []objects.Inventory
}

func (m *Memory) QueryInventory(inventoryPage int) []objects.Inventory {
	if inventoryPage <= 0 {
		return nil
	}
	startIndex := inventoryPage * PageLength
	lastIndex := startIndex + PageLength
	if startIndex > len(m.inventory) {
		return nil
	} else if lastIndex > len(m.inventory) {
		return m.inventory[startIndex:]
	}
	return m.inventory[startIndex:lastIndex]
}

func NewMemory() data.Database {
	var inventory []objects.Inventory
	for i := 1; i <= 300; i++ {
		inventory = append(inventory, generateProduct(i))
	}
	return &Memory{
		inventory: inventory,
	}
}

func generateProduct(i int) objects.Inventory {
	return objects.Inventory{
		Id:          i,
		Amount:      rand.Int() + 1,
		Name:        names[rand.Intn(len(names))],
		Description: descriptions[rand.Intn(len(descriptions))],
		Price:       rand.Float64(),
		Active:      rand.Intn(1) == 1,
		Dependency:  dependencies[rand.Intn(len(dependencies))],
		Image:       nil,
	}
}
