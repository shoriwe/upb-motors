package data

import (
	"crypto/rand"
	"encoding/hex"
	"github.com/shoriwe/upb-motors/internal/data/objects"
	"sync"
)

type Cache struct {
	sessions            map[string]*objects.User
	sessionsMutex       *sync.Mutex
	passwordResets      map[string]*objects.User
	passwordResetsMutex *sync.Mutex
}

func (cache *Cache) generateKey() string {
	result := make([]byte, 32)
	_, readError := rand.Read(result)
	if readError != nil {
		panic(readError)
	}
	return hex.EncodeToString(result)
}

func (cache *Cache) NewUserSession(user *objects.User) string {
	cache.sessionsMutex.Lock()
	defer cache.sessionsMutex.Unlock()
	cookie := cache.generateKey()
	cache.sessions[cookie] = user
	return cookie
}

func (cache *Cache) DeleteUserSession(cookie string) bool {
	cache.sessionsMutex.Lock()
	defer cache.sessionsMutex.Unlock()
	_, found := cache.sessions[cookie]
	if found {
		delete(cache.sessions, cookie)
	}
	return found
}

func NewCache() *Cache {
	return &Cache{}
}
