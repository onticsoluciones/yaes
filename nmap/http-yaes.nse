local http = require "http"
local shortport = require "shortport"

description = [[
Checks for Ecommerce system running and vulnerabilities.
]]

author = "Ontic Soluciones"
license = "Same as Nmap--See http://nmap.org/book/man-legal.html"
categories = {"default", "discovery", "intrusive"}

portrule = shortport.http

action = function(host, port)

    command = string.format(" yaes scan %s:%s", host["targetname"], port["number"])
    local handle = io.popen(command)
    local response = handle:read("*a")
    handle:close()
    return response

end