package objects

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
