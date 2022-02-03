package controller

import (
	"github.com/gin-gonic/gin"
	"github.com/shoriwe/upb-motors/internal/data"
	"github.com/shoriwe/upb-motors/internal/data/objects"
	"github.com/shoriwe/upb-motors/internal/logs"
)

type Controller struct {
	Connection *data.Connection
	*logs.Logger
}

func (controller *Controller) Logout(context *gin.Context, cookie string) bool {
	return controller.Connection.Cache.DeleteUserSession(cookie)
}

func (controller *Controller) Login(context *gin.Context, email, password string) (*objects.User, string, bool) {
	user, loginError, succeed := controller.Connection.DB.Login(email, password)
	if succeed {
		cookies := controller.Connection.Cache.NewUserSession(user)
		go controller.LogLoginAttempt(context, email, true)
		return user, cookies, true
	} else if loginError != nil {
		go controller.LogError(context, loginError)
	}
	go controller.LogLoginAttempt(context, email, false)
	return user, "", false
}

func NewController(connection *data.Connection, logger *logs.Logger) *Controller {
	return &Controller{
		Connection: connection,
		Logger:     logger,
	}
}
