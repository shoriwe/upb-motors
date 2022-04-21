package data

import (
	"github.com/shoriwe/upb-motors/internal/data/objects"
)

type Database interface {
	Authenticate(apiKey string) bool
	APIKey(newAPIKey string)
	QueryInventory(inventoryPage int) []objects.Inventory
	GetVehicle(id int) *objects.Inventory
	Clients(page int) []objects.Client
	UploadClient(client objects.Client) error
	UploadSale(sale objects.Sale) error
}
