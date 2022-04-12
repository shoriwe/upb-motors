package sql

import (
	"database/sql"
	"encoding/base64"
	"fmt"
	_ "github.com/go-sql-driver/mysql"
	"github.com/shoriwe/upb-motors/internal/data"
	"github.com/shoriwe/upb-motors/internal/data/memory"
	"github.com/shoriwe/upb-motors/internal/data/objects"
	"sync"
	"time"
)

type SQL struct {
	apiKey      string
	apiKeyMutex *sync.Mutex
	db          *sql.DB
}

func (s *SQL) Authenticate(apiKey string) bool {
	s.apiKeyMutex.Lock()
	defer s.apiKeyMutex.Unlock()
	return s.apiKey == apiKey
}

func (s *SQL) Clients(page int) (result []objects.Client) {
	rows, queryError := s.db.Query(
		`SELECT
	clientes.id AS id,
	clientes.nombre_completo AS nombre_completo,
	clientes.cedula AS cedula,
	clientes.direccion AS direccion,
	clientes.telefono AS telefono,
	clientes.correo AS correo,
	clientes.habilitado AS habilitado
FROM
    clientes
ORDER BY clientes.id ASC
LIMIT ? OFFSET ?`, memory.PageLength, (page*memory.PageLength)-memory.PageLength)
	if queryError != nil {
		fmt.Println(queryError)
		return nil
	}
	var row objects.Client
	for rows.Next() {
		scanError := rows.Scan(
			&row.DatabaseId,
			&row.Name,
			&row.PersonalId,
			&row.Address,
			&row.PhoneNumber,
			&row.Email,
			&row.Enabled)
		if scanError != nil {
			return nil
		}
		result = append(result, row)
	}
	return result
}

func (s *SQL) UploadClient(client objects.Client) error {
	_, err := s.db.Exec(
		`INSERT INTO clientes (
	nombre_completo,
	cedula,
	direccion,
	telefono,
	correo,
	habilitado
) VALUES (
	?,
	?,
	?,
	?,
	?,
	?
);`,
		client.Name,
		client.PersonalId,
		client.Address,
		client.PhoneNumber,
		client.Email,
		client.Enabled)
	return err
}

func (s *SQL) UploadSale(sale objects.Sale) error {
	_, err := s.db.Exec(
		`INSERT INTO ventas_externas (
	product_id, precio_venta, fecha_venta
) VALUES (
	?,
	?,
	?
);`,
		sale.VehicleId,
		sale.SalePrice,
		sale.SaleDate)
	return err
}

func (s *SQL) APIKey(newAPIKey string) {
	s.apiKeyMutex.Lock()
	defer s.apiKeyMutex.Unlock()
	s.apiKey = newAPIKey
}

func (s *SQL) QueryInventory(inventoryPage int) (result []objects.Inventory) {
	rows, queryError := s.db.Query(
		`SELECT
	inventario.id AS Id,
	inventario.cantidad AS Amount,
	inventario.nombre AS Name,
	inventario.descripcion AS Description,
	inventario.precio AS Price,
	inventario.activo AS Active,
	dependencias.nombre AS Dependency,
	inventario.imagen AS Image
FROM
    dependencias,
	inventario
WHERE
	dependencias.id = inventario.dependencia_id
ORDER BY inventario.id ASC
LIMIT ? OFFSET ?`, memory.PageLength, (inventoryPage*memory.PageLength)-memory.PageLength)
	if queryError != nil {
		return nil
	}
	var row objects.Inventory
	var rowImage []byte
	var rowPrice float64
	for rows.Next() {
		scanError := rows.Scan(
			&row.Id,
			&row.Amount,
			&row.Name,
			&row.Description,
			&rowPrice,
			&row.Active,
			&row.Dependency,
			&rowImage)
		if scanError != nil {
			return nil
		}
		row.Price = int64(rowPrice)
		row.Image = base64.StdEncoding.EncodeToString(rowImage)
		result = append(result, row)
	}
	return result
}

func NewSQL(dataSourceName string) data.Database {
	db, err := sql.Open("mysql", dataSourceName)
	if err != nil {
		panic(err)
	}
	// See "Important settings" section.
	db.SetConnMaxLifetime(time.Minute * 3)
	db.SetMaxOpenConns(10)
	db.SetMaxIdleConns(10)
	return &SQL{
		apiKeyMutex: new(sync.Mutex),
		db:          db,
	}
}
