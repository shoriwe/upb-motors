package web

import (
	"github.com/gin-gonic/gin"
	"github.com/shoriwe/upb-motors/internal/data"
	"github.com/shoriwe/upb-motors/internal/logs"
	"net/http"
)

func NewEngine(connection *data.Connection, logger *logs.Logger) *gin.Engine {
	router := gin.Default()
	// c := controller.NewController(connection, logger)
	router.Use(gin.Logger())
	router.GET(RootLocation, func(context *gin.Context) {
		context.Redirect(http.StatusFound, LoginLocation)
	})
	router.GET(IndexLocation, func(context *gin.Context) {
		context.Redirect(http.StatusFound, LoginLocation)
	})
	router.GET(LoginLocation)
	return router
}
