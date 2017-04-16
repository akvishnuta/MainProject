import sys
import alsaaudio
import numpy as np
from reedsolo import RSCodec, ReedSolomonError
import math
import pyaudio
import struct

HANDSHAKE_START_HZ = 8192
HANDSHAKE_END_HZ = 8192 + 512

START_HZ = 1024
STEP_HZ = 256
BITS = 4
FEC_BYTES = 4


frequency_array=[]
sample_rate=44100
duration=0.1
sample_size=round(duration*sample_rate);


def generate(frequency):
    sample= []
    increment=2.0*math.pi*frequency/sample_rate
    angle=0;
    i=0
    while(i<sample_size):
        sample.append(math.sin(angle)*32767)
        angle+=increment
        i+=1
    return sample;
    
def playSound(samples):
    p = pyaudio.PyAudio()
    duration = 1.0   # in seconds, may be float
    sample_buffer=struct.pack('f'*len(samples), *samples)
    stream = p.open(format=pyaudio.paFloat32,channels=1,rate=sample_rate,output=True)
    stream.write(sample_buffer)
    stream.stop_stream()
    stream.close()
    p.terminate()

def encode_bitchunks(chunks):
    out_bytes=[]
    for m in chunks:
        out_bytes.append((m & 0xf0)>>4)
        out_bytes.append(m & 0x0f)

    return out_bytes


def play_linux(frame_rate=44100, interval=0.1):
    message=raw_input("Enter your message")
    b=bytearray()
    b.extend(message)
    msg=[]
    for m in b:
        msg.append(m & 0xFF)
        print m
    fec_payload=RSCodec(FEC_BYTES).encode(msg)
    deco = RSCodec(FEC_BYTES).decode(fec_payload)
    for f in fec_payload:
        print f

    frequency_array.append(HANDSHAKE_START_HZ)
    fec_payload_4bits=encode_bitchunks(fec_payload)
    print fec_payload_4bits
    for byte in fec_payload_4bits:
        frequency_array.append(START_HZ+byte*STEP_HZ)
    frequency_array.append(HANDSHAKE_END_HZ)
    
    print frequency_array
    samples=[]
    gen=[]
    for freq in frequency_array:
        gen=generate(freq)
        for g in gen:
            samples.append(g)
    playSound(samples)
    
if __name__ == '__main__':
    play_linux()
