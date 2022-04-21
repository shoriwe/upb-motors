FROM golang:1.18.1-buster

COPY cmd /opt/upb-motors/cmd
COPY internal /opt/upb-motors/internal
COPY vendor /opt/upb-motors/vendor
COPY go.mod /opt/upb-motors/go.mod
COPY go.sum /opt/upb-motors/go.sum

RUN groupadd -g 5000 -r api
RUN useradd -g 5000 -u 5000 -M -d /opt/upb-motors -s /sbin/nologin -r api

WORKDIR /opt/upb-motors

RUN go build -buildvcs=false -ldflags="-s -w" -trimpath -mod vendor -o ./api ./cmd/api

CMD ./api