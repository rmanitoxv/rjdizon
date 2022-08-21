hour = 1
minute = 30
t = hour * 60 + minute + 45
hour, minute = divmod(t, 60)
print("{:02d}:{:02d}".format(hour, minute))