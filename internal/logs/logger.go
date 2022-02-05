package logs

import (
	"github.com/gin-gonic/gin"
	"io"
	"log"
)

type Logger struct {
	errorLogger *log.Logger
	debugLogger *log.Logger
}

func (logger *Logger) LogError(context *gin.Context, err error) {
	logger.errorLogger.Printf("%s %s %s", context.Request.RemoteAddr, context.Request.RequestURI, err)
}

func (logger *Logger) LogVisit(context *gin.Context) {
	logger.debugLogger.Printf("%s %s %s", context.Request.RemoteAddr, context.Request.Method, context.Request.RequestURI)
}

func (logger *Logger) LogLoginAttempt(context *gin.Context, username string, succeed bool) {
	if succeed {
		logger.debugLogger.Printf("%s succeed login as %s", context.Request.RemoteAddr, username)
	} else {
		logger.debugLogger.Printf("%s failed login as %s", context.Request.RemoteAddr, username)
	}
}

func (logger *Logger) LogCookieGeneration(context *gin.Context, username string) {
	logger.debugLogger.Printf("cookie generated for %s -> %s", username, context.Request.RemoteAddr)
}

func (logger *Logger) LogAuthRequired(context *gin.Context) {
	logger.debugLogger.Printf("request from %s to %s blocked AUTH REQUIRED", context.Request.RemoteAddr, context.Request.RequestURI)
}

func (logger *Logger) LogMethodNotAllowed(context *gin.Context) {
	logger.debugLogger.Printf("%s %s not allowed for %s", context.Request.RemoteAddr, context.Request.Method, context.Request.RequestURI)
}

func (logger *Logger) LogBannedByLimit(context *gin.Context) {
	logger.debugLogger.Printf("Banned %s cause of limit exceed of path %s", context.Request.RemoteAddr, context.Request.RequestURI)
}

func (logger *Logger) LogUserNotFound(context *gin.Context, username string) {
	logger.debugLogger.Printf("User %s requested %s by not found", username, context.Request.RemoteAddr)
}

func (logger *Logger) LogSystemUpdatePassword(context *gin.Context, username string, succeed bool) {
	if succeed {
		logger.debugLogger.Printf("Successfully force password update for %s FROM %s", username, context.Request.RemoteAddr)
	} else {
		logger.debugLogger.Printf("Failed to force password update for %s FROM %s", username, context.Request.RemoteAddr)
	}
}

func (logger *Logger) LogUpdatePassword(context *gin.Context, username string, succeed bool) {
	if succeed {
		logger.debugLogger.Printf("Successfully updated password for %s by %s", username, context.Request.RemoteAddr)
	} else {
		logger.debugLogger.Printf("Failed to updated password for %s by %s", username, context.Request.RemoteAddr)
	}
}

func NewLogger(logWriter io.Writer) *Logger {
	return &Logger{
		errorLogger: log.New(logWriter, "ERROR: ", log.Ldate|log.Ltime),
		debugLogger: log.New(logWriter, "DEBUG: ", log.Ldate|log.Ltime),
	}
}
