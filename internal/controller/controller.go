package controller

import (
	"embed"
	"github.com/gin-gonic/gin"
	"github.com/shoriwe/upb-motors/internal/data"
	"github.com/shoriwe/upb-motors/internal/data/objects"
	"github.com/shoriwe/upb-motors/internal/logs"
	"io/fs"
	"path"
)

type Controller struct {
	Connection *data.Connection
	PagesFS    *embed.FS
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

func (controller *Controller) OpenPage(p string) (fs.File, error) {
	return controller.PagesFS.Open(path.Join("pages/", p))
}

func NewController(connection *data.Connection, logger *logs.Logger, static, pages *embed.FS) *Controller {
	return &Controller{
		Connection: connection,
		Logger:     logger,
		PagesFS:    pages,
	}
}
