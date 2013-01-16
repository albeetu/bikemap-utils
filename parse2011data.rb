#!/usr/bin/ruby
# read input file
# Use Primary Rd as token
# All data segments between "Primary Rd" -> array
# parse out data from each array segment
# export to csv
#
# Data segments
# "Primary Rd" 
# "Secondary Rd"
# "Collision Date"
# "Collision Type"

require 'rubygems'
require 'pp'



def readfile(file)
  incidents = Array.new()
  refined = Array.new()
  grabber = Hash.new()
  block = String.new
  counter = 0
  incident_file = File.new(file)
  while (line = incident_file.gets)
    if (line =~ /Primary Rd/)
      grabber['Primary Rd'] = line.chomp
      refined[counter] = grabber
      incidents[counter] = block
      counter = counter + 1
      block = ""
    end
    if (line =~ /Secondary Rd/)
      grabber['Secondary Rd'] = line.chomp
    end
    if (line =~/Collision Date/)
      #grab next line
      grabber['Collision Date'] = line.chomp + ', ' + incident_file.gets.chomp
    end
    if (line =~/Collision Type/) 
      grabber['Collision Type'] = incident_file.gets.chomp
    end
    block += line
  end
  puts "#{counter} incidents"
  return [refined,incidents]
end

def main()
  incidents = Array.new()
  refined = Array.new()
  incidents = readfile('labikemap2011.txt')
  pp incidents[0]
end

main()



