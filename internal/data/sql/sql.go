package sql

import (
	"database/sql"
	"fmt"
	_ "github.com/go-sql-driver/mysql"
	"github.com/shoriwe/upb-motors/internal/data"
	"github.com/shoriwe/upb-motors/internal/data/memory"
	"github.com/shoriwe/upb-motors/internal/data/objects"
	"time"
)

type SQL struct {
	db *sql.DB
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
	dependencias.nombre AS Name,
	inventario.imagen AS Image
FROM
    dependencias,
	inventario
WHERE
	dependencias.id = inventario.dependencia_id
ORDER BY inventario.id ASC
LIMIT ?, ?`, inventoryPage, memory.PageLength)
	if queryError != nil {
		fmt.Println(queryError)
		return nil
	}
	var row objects.Inventory
	for rows.Next() {
		scanError := rows.Scan(
			&row.Id,
			&row.Amount,
			&row.Name,
			&row.Description,
			&row.Price,
			&row.Active,
			&row.Name,
			&row.Image)
		if scanError != nil {
			return nil
		}
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
		db: db,
	}
}
