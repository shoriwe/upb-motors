package data

import (
	"encoding/hex"
	"github.com/shoriwe/upb-motors/internal/data/objects"
	"golang.org/x/crypto/sha3"
)

type Database interface {
	Login(email, password string) (user *objects.User, err error, succeed bool)
}

func CheckPasswords(password, hashedPassword string) bool {
	hash := sha3.New512()
	hash.Write([]byte(password))
	return hex.EncodeToString(hash.Sum(nil)) == hashedPassword
}

type Connection struct {
	DB    Database
	Cache *Cache
}

func NewConnection(database Database) *Connection {
	return &Connection{
		DB:    database,
		Cache: NewCache(),
	}
}
