#include <fstream>
#include <iostream>
using namespace std;
ifstream f("scmax.in");
ofstream g("scmax.out");
int a[100002],v[100002],d[100002],n,i,p,L,pmax;
int binarySearch(int x,int left,int right)
{
  int pmax=0;
  while(left<=right)
  {
    int m=(left+right)>>1;
    if(x>a[m])
    {
      left=m+1;
      pmax=m;
    }
    else
    {
      right=m-1;
    }
  }
  return pmax;
}
int main()
{
  f>>n;
  for(i=1;i<=n;i++)
    f>>v[i];

  a[0]=-1;
  L=0; //length of solution array a
  for(i=1;i<=n;i++)
  {
    pmax=binarySearch(v[i],0,L);
    if(pmax+1>L)
      L=pmax+1;
    a[pmax+1]=v[i];
    d[i]=pmax+1;
  }
  int mx=0;
  for(i=1;i<=n;i++)
    if(d[i]>mx)
    {
      mx=d[i];
      p=i;
    }

  g<<mx<<endl;
  int sz=mx;
  a[mx]=v[p];

  for(i=p-1;i>=1;i--)
    if(d[i]==d[p]-1 && v[i]<=v[p])
    {
      p=i;
      mx--;
      a[mx]=v[p];
    }
  for(i=1;i<=sz;i++)
    g<<a[i]<<' ';
}
