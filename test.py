import socketio
from glob import glob
import os
import threading
import ctypes
import time
import json
import requests
import websockets
from websockets.exceptions import ConnectionClosed
# from helpers import *
# import psycopg2
from random import randint
import urllib.request
from datetime import datetime
# from getpass import getpass
import gpxpy 
import gpxpy.gpx
# from tqdm import tqdm
# from geopy.distance import geodesic as GD
                


async def main():
    #async with websockets.serve(handler, *LISTEN_ADDRESS):
    async with websockets.serve(handler, *LISTEN_ADDRESS, process_request=health_check):
        await br_msg()

async def br_msg():
    while True:
        try:
            gpx_file = open('./gpx/1A.gpx', 'r') 
            gpx = gpxpy.parse(gpx_file)

            _total = 0
            for track in gpx.tracks: 
                for segment in track.segments: 
                    for point in segment.points:
                        _total += 1
                        tick_speed = randint(2,5)
                        if _total % tick_speed != 0:
                            print('--skip')
                            time.sleep(0.1)
                            pass
                        else:
                            print('Point at ({0},{1}) -> {2}'.format(point.latitude, point.longitude, point.elevation) )
                            # time.sleep(randint(3,10))
                            time.sleep(4)
            await asyncio.sleep(10)
        except websockets.ConnectionClosed:
            pass

if __name__ == "__main__":
    try:
        clearConsole()
        printInfo("[#] Server running on: " + str(LISTEN_ADDRESS) + ".")
        asyncio.run(main())
    except KeyboardInterrupt:
        printWarning('[x] Stopped')
        