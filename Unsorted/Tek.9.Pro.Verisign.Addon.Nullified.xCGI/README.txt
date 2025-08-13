To setup Verisign Payflow Pro on Windows Servers:

1: Copy this \Verisign_Payflow_Pro\ directory to the secured directory on your server where your database resides.
   Example: C:\InetSecure\databases\yourdomain.com\Verisign_Payflow_Pro\

2: Double-click, and run the PFProCOMSetup.exe on the server from this new location.

3: Copy the pfpro.dll file to the C:\WINNT\System32\ directory on the server.

4: Set the server Environment Variable for your Cert path:
   * You must set the environment variable PFPRO_CERT_PATH to point to the directory that 
    contains your f73e89fd.0 file, in the \certs\ subdirectory..:
   ** To set the environmental variable under Windows NT 4.0, right click on "My Computer", select "properties", 
      select "Environment", create a new "System Variable" PFPRO_CERT_PATH with the value equal to your certs 
      subdirectory. You must reboot the server for this change to take effect.
   ** To set the environmental variable under Windows 2000, right click on "My Computer", select "properties", 
      select "Advanced", select "Environmental Variables", create a new "System Variable" PFPRO_CERT_PATH with 
      the value equal to your certs subdirectory. You must restart the World Wide Web Publishing Service on the 
      server for this change to take effect.

5: Apply at least Read permissions, for the IUSR_machinename account to the entire \certs\ subdirectory, and all files 
   within. The f73e89fd.0 file should be the only file in your \certs\ subdirectory.

6: Done! As long as you have the correct account info set in your Intranet Global Editor (tblGlobal) for Verisign Payflow
Pro, then your website is now set to connect to the Verisign Servers and process credit cards in real-time on your website.

