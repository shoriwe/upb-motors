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
	if succeed {
		if data.CheckPasswords(password, user.Password) {
			return user, nil, true
		}
	}
	return nil, nil, false
}

func NewMemory() data.Database {
	return &Memory{
		users: map[string]*objects.User{
			"admin@upb.motors.co": &objects.User{
				Email:    "admin@upb.motors.co",
				Password: "5a38afb1a18d408e6cd367f9db91e2ab9bce834cdad3da24183cc174956c20ce35dd39c2bd36aae907111ae3d6ada353f7697a5f1a8fc567aae9e4ca41a9d19d", // admin
			},
		},
		usersMutex: new(sync.Mutex),
	}
}
