package objects

import "time"

type Sale struct {
	VehicleId int
	SalePrice float64
	SaleDate  time.Time
}

type Client struct {
	DatabaseId  int    `json:"id"`
	Name        string `json:"nombre_completo"`
	PersonalId  string `json:"cedula"`
	Address     string `json:"direccion"`
	PhoneNumber string `json:"telefono"`
	Email       string `json:"correo"`
	Enabled     bool   `json:"habilitado"`
}

type Inventory struct {
	Id          int     `json:"id"`
	Amount      int     `json:"cantidad"`
	Name        string  `json:"nombre"`
	Description string  `json:"descripcion"`
	Price       float64 `json:"precio"`
	Active      bool    `json:"activo"`
	Dependency  string  `json:"dependencia"`
	Image       []byte  `json:"imagen"`
}
