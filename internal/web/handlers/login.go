package handlers

import (
	"github.com/gin-gonic/gin"
	"github.com/shoriwe/upb-motors/internal/controller"
	"github.com/shoriwe/upb-motors/internal/web/values"
	"io"
	"net/http"
)

func GetLogin(c *controller.Controller) gin.HandlerFunc {
	return func(context *gin.Context) {
		file, openError := c.OpenPage("login.html")
		if openError != nil {
			go c.LogError(context, openError)
			return
		}
		// defer file.Close()
		_, copyError := io.Copy(context.Writer, file)
		if copyError != nil {
			go c.LogError(context, openError)
		}
	}
}

func PostLogin(c *controller.Controller) gin.HandlerFunc {
	return func(context *gin.Context) {
		email := context.PostForm(values.EmailArgument)
		password := context.PostForm(values.PasswordArgument)
		_, cookie, succeed := c.Login(context, email, password)
		if succeed {
			context.SetCookie(values.CookieName, cookie, 0, "/", "", true, true)
			context.Redirect(http.StatusFound, values.DashboardLocation)
		} else {
			context.Redirect(http.StatusFound, values.LoginLocation)
		}
	}
}
