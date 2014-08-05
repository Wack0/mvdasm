mvdasm
======

This is a small MivaVM disassembler I coded when bored. (Yes, it's completely unofficial. I have no affiliation with Miva, Inc. I'm still unsure if Miva themselves have any kind of disassembler that they use internally, asides from leftover code in MivaVM and mvasm that seems to just dump opcodes anyway.)  

Given a .mvc file, it outputs .mva files that can be compiled by the official MivaVM assembler (mvasm), assuming no errors occured during disassembly.  

If you don't have any .mvc files to hand, I've included a small example hello world mvc.  

  
**What is MivaVM?**
  

It's a little known VM (which has a rich history; MivaScript used to be more popular, but the masses chose PHP) that runs on a webserver, interpreting compiled MivaScript files (.mvc).  

Its main use today is for running Miva's flagship web script, Miva Merchant.  

The documentation on MivaVM internals and the .mvc file format is basically nonexistant; this disassembler was made possible through reverse engineering. The *nix binaries being not stripped helped a lot :)

  
**Why was *a disassembler* coded in PHP?**
  

A few reasons. First, because I'm insane :P  

Second, because I had coded an extractor for something I don't want to mention yet, which used zlib compression extensively, so I used php because I could one-line that decompression, and so I had binary reading code that I essentially used verbatim from that.  

  
**I'm interested more in this VM. What next?**
   

You might want to get the [MivaScript compiler](http://www.mivamerchant.com/support/downloads) which contains the official MivaVM assembler (mvasm). For those who can't be bothered to click the link, binaries are available for Windows (x86), Linux (x86 and x64), and FreeBSD (x86 and x64).  

Regarding the VM itself, it's a stack based VM. I'm still unsure of what some of the higher level opcodes do, but if you want a list of opcodes just check the mvdasm code already.  
By the way, MivaVM is interpreted, not jitted.
