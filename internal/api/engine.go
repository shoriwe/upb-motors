package api

import (
	"github.com/gin-gonic/gin"
	"github.com/shoriwe/upb-motors/internal/data"
	"github.com/shoriwe/upb-motors/internal/data/objects"
	"net/http"
	"strconv"
	"time"
)

type Response struct {
	Succeed bool        `json:"succeed"`
	Message string      `json:"message"`
	Body    interface{} `json:"body"`
}

func inventory(database data.Database) gin.HandlerFunc {
	return func(context *gin.Context) {
		rawPage := context.Param(PageParam)
		page, parseError := strconv.Atoi(rawPage)
		if parseError != nil {
			context.JSON(http.StatusForbidden,
				Response{
					Succeed: false,
					Message: "Invalid page",
				},
			)
			return
		}
		context.JSON(http.StatusOK,
			Response{
				Succeed: true,
				Body:    database.QueryInventory(page),
			},
		)
	}
}

func ensureAuth(database data.Database) gin.HandlerFunc {
	return func(context *gin.Context) {
		if !database.Authenticate(context.GetHeader(APIKeyHeader)) {
			context.AbortWithStatus(http.StatusForbidden)
			return
		}
	}
}

func listClients(database data.Database) gin.HandlerFunc {
	return func(context *gin.Context) {
		rawPage := context.Param(PageParam)
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
		context.JSON(http.StatusOK,
			Response{
				Succeed: true,
				Body:    database.Clients(page),
			},
		)
	}
}

func uploadClient(database data.Database) gin.HandlerFunc {
	return func(context *gin.Context) {
		var request objects.Client
		bindError := context.BindJSON(&request)
		if bindError != nil {
			_ = context.AbortWithError(http.StatusForbidden, bindError)
			return
		}
		uploadError := database.UploadClient(request)
		if uploadError != nil {
			_ = context.AbortWithError(http.StatusForbidden, uploadError)
			return
		}
	}
}

type UploadSaleRequest struct {
	VehicleId int     `json:"id_vehiculo"`
	SalePrice float64 `json:"precio_venta"`
	SaleDate  string  `json:"fecha_venta"`
}

func uploadSale(database data.Database) gin.HandlerFunc {
	return func(context *gin.Context) {
		var request UploadSaleRequest
		bindError := context.BindJSON(&request)
		if bindError != nil {
			_ = context.AbortWithError(http.StatusForbidden, bindError)
			return
		}
		saleDate, parseError := time.Parse("2006/01/02", request.SaleDate)
		if parseError != nil {
			_ = context.AbortWithError(http.StatusForbidden, parseError)
			return
		}
		uploadError := database.UploadSale(objects.Sale{
			VehicleId: request.VehicleId,
			SalePrice: request.SalePrice,
			SaleDate:  saleDate,
		})
		if uploadError != nil {
			_ = context.AbortWithError(http.StatusForbidden, uploadError)
			return
		}
	}
}

func NewEngine(database data.Database) *gin.Engine {
	router := gin.Default()
	router.Use(gin.Logger())
	router.GET(InventoryLocation, inventory(database))
	private := router.Group(PrivateLocation)
	private.Use(ensureAuth(database))
	private.GET(ListClientsLocation, listClients(database))
	private.POST(UploadClientLocation, uploadClient(database))
	private.POST(UploadSaleLocation, uploadSale(database))
	return router
}
