package memory

import (
	"github.com/shoriwe/upb-motors/internal/data"
	"github.com/shoriwe/upb-motors/internal/data/objects"
	"math/rand"
	"strconv"
	"sync"
	"sync/atomic"
)

const (
	PageLength = 10
)

var (
	dependencies = []string{"Bucaramanga", "Bogota", "Giron"}
	descriptions = []string{"Mazda", "Nissan", "Ford"}
	vehicleNames = []string{"sedan", "camioneta", "deportivo"}
	clientNames  = []string{"Antonio", "Paola", "Juan", "Ana"}
)

type Memory struct {
	currentClientId int64
	apiKey          string
	apiKeyMutex     *sync.Mutex
	clients         []objects.Client
	clientsMutex    *sync.Mutex
	sales           []objects.Sale
	salesMutex      *sync.Mutex
	inventory       []objects.Inventory
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

func (m *Memory) APIKey(newAPIKey string) {
	m.apiKeyMutex.Lock()
	defer m.apiKeyMutex.Unlock()
	m.apiKey = newAPIKey
}

func (m *Memory) Authenticate(apiKey string) bool {
	m.apiKeyMutex.Lock()
	defer m.apiKeyMutex.Unlock()
	return m.apiKey == apiKey
}

func (m *Memory) Clients(page int) []objects.Client {
	m.clientsMutex.Lock()
	defer m.clientsMutex.Unlock()
	if page <= 0 {
		return nil
	}
	startIndex := page * PageLength
	lastIndex := startIndex + PageLength
	if startIndex > len(m.inventory) {
		return nil
	} else if lastIndex > len(m.inventory) {
		return m.clients[startIndex:]
	}
	return m.clients[startIndex:lastIndex]
}

func (m *Memory) UploadClient(client objects.Client) error {
	m.clientsMutex.Lock()
	defer m.clientsMutex.Unlock()
	client.DatabaseId = m.nextClientId()
	m.clients = append(m.clients, client)
	return nil
}

func (m *Memory) UploadSale(sale objects.Sale) error {
	m.salesMutex.Lock()
	defer m.salesMutex.Unlock()
	m.sales = append(m.sales, sale)
	return nil
}

func (m *Memory) nextClientId() int {
	return int(atomic.AddInt64(&m.currentClientId, 1))
}

func NewMemory() data.Database {
	var inventory []objects.Inventory
	for i := 1; i <= 300; i++ {
		inventory = append(inventory, generateProduct(i))
	}
	var clients []objects.Client
	for i := 1; i <= 300; i++ {
		clients = append(clients, generateClient(i))
	}
	return &Memory{
		currentClientId: 299,
		apiKeyMutex:     new(sync.Mutex),
		clientsMutex:    new(sync.Mutex),
		clients:         clients,
		salesMutex:      new(sync.Mutex),
		inventory:       inventory,
	}
}

func generateClient(id int) objects.Client {
	clientName := clientNames[rand.Intn(len(clientNames))]
	return objects.Client{
		DatabaseId:  id,
		Name:        clientName,
		PersonalId:  strconv.Itoa(rand.Intn(100000000)),
		Address:     "Loma 1",
		PhoneNumber: "3203203200",
		Email:       clientName + "@A" + strconv.Itoa(rand.Intn(100000000)) + "A.com",
		Enabled:     rand.Intn(2) == 1,
	}
}

func generateProduct(i int) objects.Inventory {
	return objects.Inventory{
		Id:          i,
		Amount:      rand.Int() + 1,
		Name:        vehicleNames[rand.Intn(len(vehicleNames))],
		Description: descriptions[rand.Intn(len(descriptions))],
		Price:       rand.Float64(),
		Active:      rand.Intn(2) == 1,
		Dependency:  dependencies[rand.Intn(len(dependencies))],
		Image:       nil,
	}
}
