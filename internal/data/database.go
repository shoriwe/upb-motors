package data

import (
	"github.com/shoriwe/upb-motors/internal/data/objects"
)

type Database interface {
	QueryInventory(inventoryPage int) []objects.Inventory
}
