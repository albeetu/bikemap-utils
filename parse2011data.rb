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
  block = String.new
  counter = 0
  incident_file = File.new(file)
  while (line = incident_file.gets)
    if (line =~ /Primary Rd/)
      incidents[counter] = block
      counter = counter + 1
      block = ""
    end
    block += line
  end
  puts "#{counter} incidents"
  return incidents
end

def refine(incidents)
  refine = Array.new()
  grabber = Hash.new()
  
  incidents.each{|incident|
    pp incident
    incident.scan(/Primary Rd?(.*)\n/){|w| grabber['Primary Rd'] = w[0].strip}
    incident.scan(/Secondary Rd(.*)\n/){|w| grabber['Secondary Rd'] = w[0].strip}
    incident.scan(/Collision Date(.*)\n/){|w| grabber['Collision Date'] = w[0].strip}
    pp grabber
  }
end
 
def main()
  incidents = Array.new()
  refined = Array.new()
  incidents = readfile('labikemap2011.txt')
  refined = refine(incidents)
  puts refined.count
end

main()



