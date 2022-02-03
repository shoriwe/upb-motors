package memory

import (
	"github.com/shoriwe/upb-motors/internal/data"
	"github.com/shoriwe/upb-motors/internal/data/objects"
	"sync"
)

type Memory struct {
	users      map[string]*objects.User
	usersMutex *sync.Mutex
}

func (m *Memory) Login(email, password string) (user *objects.User, err error, succeed bool) {
	m.usersMutex.Lock()
	defer m.usersMutex.Unlock()
	user, succeed = m.users[email]
	if data.CheckPasswords(password, user.Password) {
		return user, nil, true
	}
	return nil, nil, false
}

func NewMemory() data.Database {
	return &Memory{}
}
