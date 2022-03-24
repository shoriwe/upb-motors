package web

import (
	"github.com/gin-gonic/gin"
	"github.com/shoriwe/upb-motors/internal/data"
	"github.com/shoriwe/upb-motors/internal/data/objects"
	"github.com/shoriwe/upb-motors/internal/web/values"
	"strconv"
)

type Response struct {
	Succeed bool                `json:"succeed"`
	Message string              `json:"message"`
	Body    []objects.Inventory `json:"body"`
}

func inventory(database data.Database) gin.HandlerFunc {
	return func(context *gin.Context) {
		rawPage := context.Param("page")
		page, parseError := strconv.Atoi(rawPage)
		if parseError != nil {
			context.JSON(403,
				Response{
					Succeed: false,
					Message: "Invalid page",
				},
			)
			return
		}
		context.JSON(200,
			Response{
				Succeed: true,
				Body:    database.QueryInventory(page),
			},
		)
	}
}

func NewEngine(database data.Database) *gin.Engine {
	router := gin.Default()
	router.Use(gin.Logger())
	router.GET(values.InventoryLocation, inventory(database))
	return router
}
